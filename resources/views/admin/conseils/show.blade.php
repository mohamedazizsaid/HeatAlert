@extends('layouts.admin.admin')

@section('title', 'Détails du Conseil — ' . $conseil->titre)

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.conseils.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste des conseils
      </a>
    </nav>
    <div class="d-flex align-items-center gap-3">
      <h1><i class="fa-solid fa-lightbulb me-2 text-warning"></i>{{ $conseil->titre }}</h1>
      @if($conseil->actif)
        <span class="badge bg-success" style="font-size:.85rem;"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
      @else
        <span class="badge bg-secondary" style="font-size:.85rem;"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
      @endif
      @if($conseil->niveau_alerte_cible)
        @php
          $niveauClass = match($conseil->niveau_alerte_cible) {
            'rouge'  => 'bg-danger',
            'orange' => 'bg-warning text-dark',
            'jaune'  => 'bg-info text-dark',
            default  => 'bg-success',
          };
        @endphp
        <span class="badge {{ $niveauClass }}" style="font-size:.85rem;">
          Cible : Vigilance {{ ucfirst($conseil->niveau_alerte_cible) }}
        </span>
      @endif
    </div>
    <p class="subtitle">Catégorie : <strong>{{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}</strong></p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.conseils.edit', $conseil) }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-pen-to-square"></i> Modifier le conseil
    </a>
    <button type="button" class="m-btn m-btn--ghost js-trigger-delete" style="border:1px solid #dc2626;color:#dc2626;"
            data-action="{{ route('admin.conseils.destroy', $conseil) }}"
            data-name="{{ $conseil->titre }}"
            data-type="le conseil"
            title="Supprimer le conseil">
      <i class="fa-solid fa-trash"></i> Supprimer
    </button>
  </div>
</div>

<div class="row g-4">
  {{-- ── COLONNE GAUCHE: Aperçu & Contenu ── --}}
  <div class="col-lg-7">
    {{-- Carte Visuelle d'Aperçu --}}
    <div class="m-card mb-4" style="overflow:hidden;">
      <div style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);padding:28px 24px;color:#fff;">
        <div class="d-flex align-items-center gap-4">
          <div style="width:68px;height:68px;border-radius:18px;background:rgba(220,38,38,.2);border:1px solid rgba(220,38,38,.4);display:flex;align-items:center;justify-content:center;font-size:2rem;color:#ef4444;flex-shrink:0;">
            <i class="{{ $conseil->icone ?: 'fa-solid fa-shield-heart' }}"></i>
          </div>
          <div>
            <div class="badge bg-danger text-uppercase mb-2" style="letter-spacing:1px;font-size:.72rem;">{{ $conseil->categorie }}</div>
            <h4 class="mb-1 text-white fw-bold">{{ $conseil->titre }}</h4>
            <small style="color:rgba(255,255,255,.6);">Recommandation officielle de protection face aux fortes chaleurs</small>
          </div>
        </div>
      </div>
      <div style="padding:24px;">
        <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size:.75rem;letter-spacing:.5px;">Contenu et consignes détaillées</h6>
        <div style="background:#f8fafc;border-left:4px solid #f97316;padding:18px 20px;border-radius:10px;font-size:.97rem;line-height:1.7;color:#334155;">
          {{ $conseil->contenu }}
        </div>
      </div>
    </div>

    {{-- Alertes associées à ce conseil --}}
    <div class="m-card">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Alertes météo appliquant ce conseil</h5>
        <span class="badge bg-light text-dark border">{{ $conseil->alertes ? $conseil->alertes->count() : 0 }} alerte(s)</span>
      </div>
      <div style="padding:0;">
        <div class="table-responsive">
          <table class="m-table mb-0">
            <thead>
              <tr>
                <th>Alerte</th>
                <th>Zone</th>
                <th>Niveau</th>
                <th>Statut</th>
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($conseil->alertes as $alerte)
              <tr>
                <td><strong>{{ Str::limit($alerte->titre, 30) }}</strong></td>
                <td>{{ $alerte->zone ? $alerte->zone->nom : 'Nationale' }}</td>
                <td>
                  @php
                    $bClass = match($alerte->niveau) {
                      'rouge'  => 'bg-danger',
                      'orange' => 'bg-warning text-dark',
                      'jaune'  => 'bg-info text-dark',
                      default  => 'bg-success',
                    };
                  @endphp
                  <span class="badge {{ $bClass }}">{{ ucfirst($alerte->niveau) }}</span>
                </td>
                <td>
                  <span class="badge {{ $alerte->statut === 'active' ? 'bg-danger' : 'bg-secondary' }}">
                    {{ ucfirst($alerte->statut) }}
                  </span>
                </td>
                <td style="text-align:right;">
                  <a href="{{ route('admin.alertes.show', $alerte) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 9px;font-size:12px;color:#2563eb;" title="Voir l'alerte">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                  <i class="fa-solid fa-bell-slash fa-2x mb-2 d-block"></i>
                  Aucune alerte spécifique liée manuellement. Ce conseil est diffusé automatiquement selon la catégorie et le niveau de risque.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- ── COLONNE DROITE: Métadonnées & Paramètres ── --}}
  <div class="col-lg-5">
    <div class="m-card mb-4">
      <div class="m-card__header" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-sliders me-2 text-primary"></i>Paramètres du conseil</h5>
      </div>
      <div style="padding:20px;">
        <table class="table table-sm table-borderless mb-0" style="font-size:.92rem;">
          <tbody>
            <tr>
              <td class="text-muted" style="width:40%;padding:10px 0;"><i class="fa-solid fa-layer-group me-2"></i>Catégorie</td>
              <td style="padding:10px 0;">
                <span class="badge bg-secondary" style="font-size:.85rem;">
                  {{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}
                </span>
              </td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:10px 0;"><i class="fa-solid fa-gauge-high me-2"></i>Niveau cible</td>
              <td style="padding:10px 0;">
                @if($conseil->niveau_alerte_cible)
                  <span class="badge {{ $niveauClass ?? 'bg-secondary' }}">
                    {{ ucfirst($conseil->niveau_alerte_cible) }}
                  </span>
                @else
                  <span class="text-muted">Tous niveaux</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:10px 0;"><i class="fa-solid fa-icons me-2"></i>Icône FontAwesome</td>
              <td style="padding:10px 0;">
                @if($conseil->icone)
                  <i class="{{ $conseil->icone }} me-1 text-danger"></i>
                  <code>{{ $conseil->icone }}</code>
                @else
                  <span class="text-muted">Par défaut</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:10px 0;"><i class="fa-solid fa-toggle-on me-2"></i>Visibilité publique</td>
              <td style="padding:10px 0;">
                @if($conseil->actif)
                  <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Publié & Actif</span>
                @else
                  <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Masqué / Inactif</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:10px 0;"><i class="fa-solid fa-calendar me-2"></i>Créé le</td>
              <td style="padding:10px 0;">{{ $conseil->created_at ? $conseil->created_at->format('d/m/Y H:i') : '—' }}</td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:10px 0;"><i class="fa-solid fa-clock-rotate-left me-2"></i>Dernière modif.</td>
              <td style="padding:10px 0;">{{ $conseil->updated_at ? $conseil->updated_at->diffForHumans() : '—' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- Conseils bonnes pratiques card --}}
    <div class="m-card" style="background:#fefce8;border:1px solid #fef08a;">
      <div style="padding:18px 20px;">
        <h6 style="color:#854d0e;font-weight:700;margin-bottom:8px;">
          <i class="fa-solid fa-circle-info me-2"></i>Impact sur les citoyens
        </h6>
        <p class="small mb-0" style="color:#713f12;line-height:1.6;">
          Ce conseil est mis en avant dans l'espace utilisateur ainsi que sur la page d'accueil publique dès que les conditions météorologiques dépassent le seuil de vigilance défini.
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
