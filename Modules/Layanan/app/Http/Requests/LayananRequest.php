<?php

namespace Modules\Layanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Layanan\Models\Layanan;

class LayananRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nama_layanan' => ['required', 'string', 'max:150'],
            'kategori_layanan' => ['required', Rule::in(array_keys(Layanan::KATEGORI))],
            'perangkat_daerah' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'tingkat_kematangan' => ['required', 'integer', 'between:1,5'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.max' => 'Nama layanan maksimal 150 karakter.',
            'kategori_layanan.required' => 'Kategori layanan wajib dipilih.',
            'kategori_layanan.in' => 'Kategori layanan tidak valid.',
            'perangkat_daerah.required' => 'Perangkat daerah pengelola wajib diisi.',
            'perangkat_daerah.max' => 'Perangkat daerah maksimal 150 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'tingkat_kematangan.required' => 'Tingkat kematangan wajib dipilih.',
            'tingkat_kematangan.integer' => 'Tingkat kematangan harus berupa angka.',
            'tingkat_kematangan.between' => 'Tingkat kematangan harus antara 1 sampai 5.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }
}
