<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmenuStoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('pusjamu');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'submenu' => 'required|string',
            'status' => 'required|boolean',
            'roles' => 'required|array',
        ];
    }
}
