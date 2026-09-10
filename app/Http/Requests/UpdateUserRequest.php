<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email,'.$this->user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            // Blade menyembunyikan opsi "Super Admin" dari non-Super Admin,
            // tapi ini bentengnya di server: memberi role Super Admin hanya
            // boleh dilakukan oleh Super Admin sendiri, tidak peduli permission
            // "users edit" yang dipegang pengirim request.
            'roles.*' => ['string', 'exists:roles,name', function ($attribute, $value, $fail) {
                if ($value === 'Super Admin' && ! $this->user()?->hasRole('Super Admin')) {
                    $fail('Hanya Super Admin yang dapat memberikan role Super Admin.');
                }
            }],
        ];
    }
}
