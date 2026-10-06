<?php

namespace App\Http\Requests;

use App\Models\EquipementSensible;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipementSensibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(EquipementSensible::TYPES)],
            'description' => ['nullable', 'string', 'max:2000'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'zone_id' => ['nullable', 'exists:zones,id'],
            'niveau_sensibilite' => ['required', Rule::in(EquipementSensible::NIVEAUX)],
            'actif' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l’équipement est obligatoire.',
            'nom.max' => 'Le nom ne peut pas dépasser 150 caractères.',
            'type.required' => 'Le type d’équipement est obligatoire.',
            'type.in' => 'Le type d’équipement sélectionné est invalide.',
            'description.max' => 'La description ne peut pas dépasser 2 000 caractères.',
            'adresse.max' => 'L’adresse ne peut pas dépasser 255 caractères.',
            'zone_id.exists' => 'La zone sélectionnée n’existe pas.',
            'niveau_sensibilite.required' => 'Le niveau de sensibilité est obligatoire.',
            'niveau_sensibilite.in' => 'Le niveau de sensibilité sélectionné est invalide.',
            'actif.boolean' => 'Le statut actif est invalide.',
        ];
    }
}
