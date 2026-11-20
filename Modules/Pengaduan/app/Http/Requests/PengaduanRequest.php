<?php

namespace Modules\Pengaduan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengaduanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * The file is required when creating, and optional when updating
     * (keep the old file).
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $pengaduan = $this->route('pengaduan');

        return [
            'layanan_id' => [
                'required',
                'exists:layanan,id',
            ],

            'deskripsi_pengaduan' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],

            'file_bukti' => [
                $pengaduan ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:2048',
            ],
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
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'layanan_id.exists' => 'Layanan yang dipilih tidak ditemukan.',
            'deskripsi_pengaduan.required' => 'Deskripsi kendala wajib diisi.',
            'deskripsi_pengaduan.min' => 'Deskripsi kendala minimal 10 karakter.',
            'deskripsi_pengaduan.max' => 'Deskripsi kendala maksimal 2000 karakter.',
            'file_bukti.required' => 'File bukti wajib diunggah.',
            'file_bukti.file' => 'File bukti tidak valid.',
            'file_bukti.mimes' => 'File bukti harus berformat JPG, PNG, PDF, DOC, atau DOCX.',
            'file_bukti.max' => 'Ukuran file bukti maksimal 2 MB.',
            'file_bukti.uploaded' => 'File bukti gagal diunggah (maksimal 2 MB).',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
