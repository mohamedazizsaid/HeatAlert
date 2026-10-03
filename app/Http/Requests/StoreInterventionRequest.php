<?php

namespace App\Http\Requests;

use App\Models\Intervention;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInterventionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupure_id' => [
                'required',
                Rule::exists('coupures', 'id')->where(function ($query) {
                    $query->whereIn('type', ['panne', 'surcharge']);
                }),
            ],
            'equipe' => ['required', 'string', 'max:150'],
            'date_prevue' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $coupure = $this->route('coupure');

                    if ($coupure && \Carbon\Carbon::parse($value)->lt($coupure->date_debut)) {
                        $fail('La date de l’intervention doit être égale ou postérieure au début de la coupure.');
                    }
                },
            ],
            'duree_estimee_min' => ['required', 'integer', 'min:1', 'max:10080'],
            'statut' => ['required', 'string', Rule::in(Intervention::STATUTS)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'coupure_id.required' => 'La coupure est obligatoire.',
            'coupure_id.exists' => 'Une intervention est possible uniquement pour une panne ou une surcharge.',
            'equipe.required' => 'L’équipe d’intervention est obligatoire.',
            'equipe.string' => 'L’équipe doit être une chaîne de caractères.',
            'equipe.max' => 'Le nom de l’équipe ne peut pas dépasser 150 caractères.',
            'date_prevue.required' => 'La date prévue est obligatoire.',
            'date_prevue.date' => 'La date prévue doit être une date valide.',
            'duree_estimee_min.required' => 'La durée estimée est obligatoire.',
            'duree_estimee_min.integer' => 'La durée estimée doit être un nombre entier de minutes.',
            'duree_estimee_min.min' => 'La durée estimée doit être supérieure à zéro.',
            'duree_estimee_min.max' => 'La durée estimée ne peut pas dépasser 7 jours.',
            'statut.required' => 'Le statut de l’intervention est obligatoire.',
            'statut.in' => 'Le statut de l’intervention sélectionné est invalide.',
            'notes.string' => 'Les notes doivent être une chaîne de caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 2000 caractères.',
        ];
    }
}
