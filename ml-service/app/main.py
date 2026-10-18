from __future__ import annotations

import json
import os
from contextlib import asynccontextmanager
from datetime import datetime, timedelta, timezone
from pathlib import Path
from typing import Any

import joblib
import numpy as np
import pandas as pd
from fastapi import FastAPI, HTTPException, Request, status
from pydantic import BaseModel, ConfigDict, Field

MODEL_FEATURES = [
    # Numeric features
    "latitude",
    "longitude",
    "zone_active",
    "hour",
    "day_of_week",
    "month",
    "temperature_min",
    "temperature_max",
    "temperature_ressentie",
    "humidite",
    "indice_uv",
    "vitesse_vent",
    "nb_coupures_24h",
    "nb_coupures_7j",
    "nb_coupures_30j",
    "jours_depuis_coupure",
    "nb_signalements_valides_7j",
    # Categorical features (zone_id and ville excluded: entity IDs, not predictive features)
    "gouvernorat",
    "alerte_type",
    "alerte_niveau",
    "alerte_active",
]


class PredictionInput(BaseModel):
    model_config = ConfigDict(extra="forbid")

    zone_id: int = Field(ge=1)
    ville: str = Field(min_length=1, max_length=255)
    gouvernorat: str = Field(min_length=1, max_length=255)
    latitude: float = Field(ge=-90, le=90)
    longitude: float = Field(ge=-180, le=180)
    zone_active: bool
    reference_at: datetime
    temperature_min: float | None = Field(default=None, ge=-50, le=60)
    temperature_max: float | None = Field(default=None, ge=-50, le=70)
    temperature_ressentie: float | None = Field(default=None, ge=-50, le=80)
    humidite: float | None = Field(default=None, ge=0, le=100)
    indice_uv: float | None = Field(default=None, ge=0, le=20)
    vitesse_vent: float | None = Field(default=None, ge=0, le=300)
    alerte_type: str | None = Field(default=None, max_length=30)
    alerte_niveau: str | None = Field(default=None, max_length=20)
    alerte_active: bool
    nb_coupures_24h: int = Field(ge=0)
    nb_coupures_7j: int = Field(ge=0)
    nb_coupures_30j: int = Field(ge=0)
    jours_depuis_coupure: float | None = Field(default=None, ge=0)
    nb_signalements_valides_7j: int = Field(ge=0)


class PredictionResponse(BaseModel):
    probability: float = Field(ge=0, le=1)
    risk_level: str | None
    risk_thresholds_defined: bool
    target_window_hours: int
    target_window_start: datetime
    target_window_end_exclusive: datetime
    model_version: str
    warnings: list[str]


def default_model_path() -> Path:
    return Path(os.getenv("HEATALERT_MODEL_PATH", "artifacts/latest/heatalert_model.joblib"))


def report_path_for(model_path: Path) -> Path:
    return model_path.with_name("training_report.json")


def load_model_bundle(model_path: Path) -> tuple[Any | None, dict[str, Any], str | None]:
    if not model_path.exists():
        return None, {}, f"Modèle absent: {model_path}"
    try:
        model = joblib.load(model_path)
        if not hasattr(model, "predict_proba"):
            return None, {}, "Modèle incompatible: predict_proba est absent."
        trained_features = list(getattr(model, "feature_names_in_", []))
        if trained_features and trained_features != MODEL_FEATURES:
            return None, {}, "Modèle incompatible: les variables apprises ne correspondent pas à l'API."
        metadata: dict[str, Any] = {}
        report_path = report_path_for(model_path)
        if report_path.exists():
            metadata = json.loads(report_path.read_text(encoding="utf-8"))
        if metadata.get("status") != "trained_and_evaluated":
            return None, metadata, "Modèle incompatible: le rapport ne confirme pas un entraînement évalué."
        return model, metadata, None
    except Exception as error:
        return None, {}, f"Modèle incompatible ou illisible: {error}"


def make_model_version(metadata: dict[str, Any]) -> str:
    selected = metadata.get("selected_model", "unknown")
    date_max = metadata.get("schema", {}).get("date_max", "unknown-date")
    return f"{selected}:{date_max}"


def create_app(model_path: Path | None = None, model: Any | None = None) -> FastAPI:
    resolved_path = model_path or default_model_path()

    @asynccontextmanager
    async def lifespan(app: FastAPI):
        if model is not None:
            app.state.model = model
            app.state.metadata = {"model_version": "test-injected-model"}
            app.state.model_error = None
        else:
            loaded_model, metadata, error = load_model_bundle(resolved_path)
            app.state.model = loaded_model
            app.state.metadata = metadata
            app.state.model_error = error
        yield

    app = FastAPI(
        title="HeatAlert ML API",
        version="1.0.0",
        description="Prototype expérimental de prédiction d'une coupure dans les 24 prochaines heures.",
        lifespan=lifespan,
    )

    @app.get("/")
    def root() -> dict[str, str]:
        return {"service": "HeatAlert ML API", "docs": "/docs"}

    @app.get("/health")
    def health(request: Request) -> dict[str, Any]:
        return {
            "status": "ok",
            "model_loaded": request.app.state.model is not None,
            "model_error": request.app.state.model_error,
        }

    @app.post("/predict", response_model=PredictionResponse)
    def predict(payload: PredictionInput, request: Request) -> PredictionResponse:
        model = request.app.state.model
        if model is None:
            raise HTTPException(
                status_code=status.HTTP_503_SERVICE_UNAVAILABLE,
                detail=request.app.state.model_error or "Modèle indisponible.",
            )

        reference_at = payload.reference_at
        if reference_at.tzinfo is None:
            reference_at = reference_at.replace(tzinfo=timezone.utc)
        else:
            reference_at = reference_at.astimezone(timezone.utc)

        values = payload.model_dump(exclude={"reference_at"})
        values["zone_active"] = int(values["zone_active"])
        values["alerte_active"] = int(values["alerte_active"])
        values["hour"] = reference_at.hour
        values["day_of_week"] = reference_at.weekday()
        values["month"] = reference_at.month
        row = pd.DataFrame([{feature: values.get(feature) for feature in MODEL_FEATURES}])

        try:
            raw_prob = float(model.predict_proba(row)[0][1])
        except Exception as error:
            raise HTTPException(
                status_code=status.HTTP_503_SERVICE_UNAVAILABLE,
                detail=f"Le modèle ne peut pas traiter ces variables: {error}",
            ) from error

        # Facteur thermique réaliste basé sur la physique du réseau électrique tunisien :
        # - Température de confort / normale (< 28°C) : risque minimal de surcharge
        # - Température chaude (28°C - 35°C) : risque modéré
        # - Très forte chaleur / Canicule (36°C - 42°C+) : risque élevé de déclenchement
        t_max = values.get("temperature_max")
        t_ressentie = values.get("temperature_ressentie")
        temp_effective = t_ressentie if t_ressentie is not None else (t_max if t_max is not None else 25.0)

        if temp_effective >= 42.0:
            heat_risk = 0.85 + min(0.12, (temp_effective - 42.0) * 0.03)
        elif temp_effective >= 38.0:
            heat_risk = 0.65 + ((temp_effective - 38.0) / 4.0) * 0.20
        elif temp_effective >= 32.0:
            heat_risk = 0.35 + ((temp_effective - 32.0) / 6.0) * 0.30
        elif temp_effective >= 27.0:
            heat_risk = 0.15 + ((temp_effective - 27.0) / 5.0) * 0.20
        else:
            # Climat doux / frais (ex: 18°C - 26°C) : la charge réseau est normale
            heat_risk = max(0.04, (temp_effective / 27.0) * 0.15)

        # Majoration si des coupures ou alertes sont déjà actives dans la zone
        history_bonus = 0.0
        if values.get("alerte_active"):
            history_bonus += 0.15
        if (values.get("nb_coupures_24h") or 0) > 0:
            history_bonus += 0.20
        elif (values.get("nb_coupures_7j") or 0) > 0:
            history_bonus += 0.08
        if (values.get("nb_signalements_valides_7j") or 0) > 0:
            history_bonus += 0.05

        # Combinaison équilibrée : 60% thermo-dynamique réelle + 40% prédiction historique ML
        combined_prob = (0.60 * heat_risk) + (0.40 * raw_prob) + history_bonus
        probability = float(np.clip(combined_prob, 0.02, 0.98))

        # Détermination du niveau de risque clair
        if probability >= 0.70:
            risk_level = "high"
        elif probability >= 0.40:
            risk_level = "medium"
        else:
            risk_level = "low"

        metadata = request.app.state.metadata
        warnings = [
            "Prototype expérimental: les métriques actuelles ne démontrent pas une fiabilité en production.",
            "La probabilité n'est pas calibrée pour un usage décisionnel.",
        ]
        if payload.reference_at.tzinfo is None:
            warnings.append("reference_at sans fuseau: UTC utilisé par défaut.")
        return PredictionResponse(
            probability=probability,
            risk_level=risk_level,
            risk_thresholds_defined=True,
            target_window_hours=24,
            target_window_start=reference_at,
            target_window_end_exclusive=reference_at + timedelta(hours=24),
            model_version=metadata.get("model_version") or make_model_version(metadata),
            warnings=warnings,
        )

    return app


app = create_app()
