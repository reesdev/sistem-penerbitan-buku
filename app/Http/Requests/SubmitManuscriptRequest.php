<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitManuscriptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'title'        => 'required|string|max:255',
        'description'  => 'required|string',
        'author_name'  => 'required|string|max:255',
        'biodata'      => 'required|string',
        'phone_number' => 'required|string|max:20',
        'file_pdf'     => 'required|file|mimes:pdf|max:10240',
        'cover_image'  => 'required|file|mimes:jpg,jpeg,png|max:2048',
    ];
    }
    public function messages(): array
    {
        return [
            'title.required'        => 'Judul manuskrip wajib diisi.',
            'title.max'             => 'Judul manuskrip maksimal 255 karakter.',
            'description.required'  => 'Deskripsi manuskrip wajib diisi.',
            'author_name.required'  => 'Nama penulis wajib diisi.',
            'biodata.required'      => 'Biodata penulis wajib diisi.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'file_pdf.required'     => 'File naskah PDF wajib diunggah.',
            'file_pdf.mimes'        => 'File naskah harus berformat PDF.',
            'file_pdf.max'          => 'Ukuran file PDF maksimal 10 MB.',
            'cover_image.required'  => 'Gambar sampul wajib diunggah.',
            'cover_image.mimes'     => 'Format sampul harus JPG, JPEG, atau PNG.',
            'cover_image.max'       => 'Ukuran gambar sampul maksimal 2 MB.',
        ];
    }
}
