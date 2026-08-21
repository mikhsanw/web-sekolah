<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileAllowed implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $allowedExt;

    protected $allowedMimes;

    public function __construct(?array $allowedExt = null, ?array $allowedMimes = null)
    {
        $this->allowedExt = $allowedExt ?? ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png'];
        $this->allowedMimes = $allowedMimes ?? [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'image/jpeg',
            'image/png',
        ];
    }

    public function passes($attribute, $value)
    {
        if (! $value) {
            return false;
        }

        $origName = $value->getClientOriginalName();
        // Ekstensi diambil dari nama asli (sumber paling jujur untuk double-extension).
        $extFromName = strtolower(pathinfo($origName, PATHINFO_EXTENSION) ?: '');
        // MIME asli dideteksi dari isi file, bukan dari header yang dikirim klien.
        $mime = strtolower($value->getMimeType() ?: '');

        // Keduanya wajib lolos: ekstensi DAN mime nyata harus ada di whitelist.
        return $extFromName !== ''
            && in_array($extFromName, $this->allowedExt, true)
            && $mime !== ''
            && in_array($mime, $this->allowedMimes, true);
    }

    public function message()
    {
        return 'File tidak diizinkan (ekstensi atau mime tidak dikenal).';
    }
}
