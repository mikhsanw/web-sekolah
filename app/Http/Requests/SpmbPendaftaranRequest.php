<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi formulir pendaftaran SPMB yang diisi calon peserta didik.
 */
class SpmbPendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asal_sekolah' => ['required', 'string', 'max:150'],
            'nisn' => ['required', 'string', 'digits_between:8,12'],
            'nik' => ['required', 'string', 'digits:16'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', Rule::in(array_keys(config('sekolah.enum.jenis_kelamin')))],
            'agama' => ['required', Rule::in(array_keys(config('sekolah.enum.agama')))],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_hp' => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'nama_orang_tua' => ['nullable', 'string', 'max:150'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'asal_sekolah' => 'asal sekolah',
            'nisn' => 'NISN',
            'nik' => 'NIK',
            'nama' => 'nama lengkap',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'no_hp' => 'nomor HP',
            'nama_orang_tua' => 'nama orang tua/wali',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nisn.digits_between' => 'NISN harus berupa angka 8 sampai 12 digit.',
            'no_hp.regex' => 'Format nomor HP tidak valid.',
        ];
    }
}
