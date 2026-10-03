<?php

namespace App\Http\Requests;

use App\Models\SignalementCoupure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSignalementCoupureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'coupure_id' => ['nullable', 'exists:coupures,id'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'statut_validation' => [
                'nullable',
                Rule::prohibitedIf(fn (): bool => ! $this->user()?->isAdmin()),
                Rule::in(SignalementCoupure::STATUTS_VALIDATION),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'coupure_id.exists' => 'La coupure sélectionnée n’existe pas.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'La photo doit être au format JPG, JPEG ou PNG.',
            'photo.max' => 'La photo ne peut pas dépasser 2 Mo.',
            'statut_validation.prohibited' => 'Vous n’êtes pas autorisé à définir le statut de validation.',
            'statut_validation.in' => 'Le statut de validation sélectionné est invalide.',
        ];
    }
}
