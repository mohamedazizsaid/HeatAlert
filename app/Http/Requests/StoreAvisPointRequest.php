<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvisPointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'note'        => ['required', 'integer', 'between:1,5'],
            'commentaire' => ['nullable', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.required'       => 'Veuillez attribuer une note (de 1 à 5 étoiles).',
            'note.integer'        => 'La note doit être un nombre entier.',
            'note.between'        => 'La note doit être comprise entre 1 et 5 étoiles.',
            'commentaire.min'     => 'Le commentaire doit comporter au moins 3 caractères si vous choisissez d\'en écrire un.',
            'commentaire.max'     => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ];
    }
}
