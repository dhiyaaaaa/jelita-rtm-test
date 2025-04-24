<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainMenuStoreUpdateRequest extends FormRequest
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
            'mainmenu' => 'required|string',
            'status' => 'required|boolean',
            'menus' => 'required|array',
            'deskripsi' => 'required|string',
        ];
    }
}
