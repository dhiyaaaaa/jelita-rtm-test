<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditeeAuditorStoreUpdateRequest extends FormRequest
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
            'auditor_1' => [
                'required',
                'exists:auditor,id',
                'different:auditor_2',
                'different:auditor_3',
            ],
            'auditor_2' => [
                'nullable',
                'exists:auditor,id',
                'different:auditor_1',
                'different:auditor_3',
            ],
            'auditor_3' => [
                'nullable',
                'exists:auditor,id',
                'different:auditor_1',
                'different:auditor_2',
            ],
        ];
    }
}
