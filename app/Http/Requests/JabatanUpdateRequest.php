<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JabatanUpdateRequest extends FormRequest
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
            'nama' => [
                'required',
                'string',
                Rule::unique('jabatan', 'nama')->ignore($this->jabatan),
            ],
            'type' => 'required|string|in:prodi,fakultas,universitas',
            'unik' => 'required|boolean',
            'unit' => 'required_if:type,universitas',
        ];
    }
}
