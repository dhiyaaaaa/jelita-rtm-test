<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'prodi' => 'required|array',
            'prodi.*' => 'exists:jabatan,id',

            'fakultas' => 'required|array',
            'fakultas.*' => 'exists:jabatan,id',

            'universitas' => 'required|array',
            'universitas.*' => 'exists:jabatan,id',
        ];
    }
}
