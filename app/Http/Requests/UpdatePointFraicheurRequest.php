<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePointFraicheurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'            => ['required', 'string', 'min:3', 'max:150'],
            'type'           => ['required', 'string', 'in:parc,salle_climatisee,fontaine,piscine,bibliotheque,autre'],
            'adresse'        => ['required', 'string', 'min:5', 'max:255'],
            'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'      => ['nullable', 'numeric', 'between:-180,180'],
            'horaires'       => ['nullable', 'string', 'max:255'],
            'zone_id'        => ['nullable', 'exists:zones,id'],
            'accessible_pmr' => ['nullable', 'boolean'],
            'actif'          => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'      => 'Le nom du point de fraîcheur est obligatoire.',
            'nom.min'           => 'Le nom doit comporter au moins 3 caractères.',
            'nom.max'           => 'Le nom ne peut pas dépasser 150 caractères.',
            'type.required'     => 'Le type d\'espace de fraîcheur est obligatoire.',
            'type.in'           => 'Le type sélectionné est invalide.',
            'adresse.required'  => 'L\'adresse géographique est obligatoire.',
            'adresse.min'       => 'L\'adresse doit comporter au moins 5 caractères.',
            'adresse.max'       => 'L\'adresse ne peut pas dépasser 255 caractères.',
            'latitude.numeric'  => 'La latitude doit être une coordonnée numérique valide.',
            'latitude.between'  => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.numeric' => 'La longitude doit être une coordonnée numérique valide.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
            'horaires.max'      => 'Le texte des horaires ne peut pas dépasser 255 caractères.',
            'zone_id.exists'    => 'La zone sélectionnée n\'existe pas dans la base.',
        ];
    }
}
