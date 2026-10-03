<?php

namespace App\Http\Requests;

use App\Models\Coupure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCoupureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(Coupure::TYPES)],
            'statut' => ['required', 'string', Rule::in(Coupure::STATUTS)],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'cause' => ['required', 'string', 'max:500'],
            'zone_id' => ['required', 'exists:zones,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type de coupure est obligatoire.',
            'type.in' => 'Le type de coupure sélectionné est invalide.',
            'statut.required' => 'Le statut de la coupure est obligatoire.',
            'statut.in' => 'Le statut de coupure sélectionné est invalide.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'cause.required' => 'La cause de la coupure est obligatoire.',
            'cause.string' => 'La cause doit être une chaîne de caractères.',
            'cause.max' => 'La cause ne peut pas dépasser 500 caractères.',
            'zone_id.required' => 'La zone est obligatoire.',
            'zone_id.exists' => 'La zone sélectionnée n’existe pas.',
        ];
    }
}
