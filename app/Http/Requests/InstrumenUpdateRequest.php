<?php

namespace App\Http\Requests;

use App\Models\Level;
use Illuminate\Foundation\Http\FormRequest;

class InstrumenUpdateRequest extends FormRequest
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
        $fakultasId = Level::where('slug', 'fakultas')->pluck('id')->first();
        $universitasId = Level::where('slug', 'universitas')->pluck('id')->first();

        return [
            'pernyataan' => 'required|string',
            'indikator' => 'required|string',
            'peraturan' => 'required|exists:peraturan,id',
            'pasalAyat' => 'nullable|array',
            'standar' => 'required|exists:standar,id',
            'kategori' => 'required|exists:kategori,id',
            'level' => 'required|exists:level,id',
            'jenis_pertanyaan' => 'required|exists:jenis_pertanyaan,id',
            'kriteria' => 'required|array',
            'kriteria.*' => 'required',
            'kriteria_id' => 'required|array',
            'prodi' => 'nullable|array',
            'jenjang' => 'nullable|array',
            'jabatan' => [
                'required_if:level,' . $fakultasId . ',' . $universitasId,
                'array'
            ],
            'unit' => [
                'required_if:level,' . $universitasId,
                'array'
            ]
        ];
    }

    public function messages()
    {
        return [
            'jabatan.required_if' => 'Kolom jabatan wajib diisi ketika level yang dipilih adalah Fakultas atau Universitas.',
            'jabatan.array' => 'Kolom jabatan harus berupa array.',
            'unit.required_if' => 'Kolom unit wajib diisi ketika level yang dipilih adalah Universitas.',
            'unit.array' => 'Kolom unit harus berupa array.',
        ];
    }
}
