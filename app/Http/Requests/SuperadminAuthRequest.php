<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SuperadminAuthRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validationName = $this->route()->getName(); //authLoginSuperadmin

        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($validationName) {
            case config('constants.route_name.superadmin.auth.login'):
                return [
                    'email' => 'required',
                    'password' => 'required|min:8',
                ];

            case config('constants.route_name.superadmin.auth.forgot_password'):
                return [
                    'email' => 'required',
                ];

            case config('constants.route_name.superadmin.auth.verified_otp'):
                return [
                    'email' => 'required',
                    'otp_code' => 'required'
                ];

            case config('constants.route_name.superadmin.auth.reset_password'):
                return [
                    'permission_code' => 'required',
                    'new_password' => 'required',
                    'confirm_password' => 'required'
                ];

            case config('constants.route_name.superadmin.auth.change_password'):
                return [
                    'old_password' => 'required',
                    'new_password' => 'required',
                    'confirm_password' => 'required'
                ];

            case config('constants.route_name.superadmin.auth.update_profile'):
                return [
                    'name' => 'required',
                    'email' => 'required',
                ];

            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'username.required' =>  __('validation.required', ['attribute' => 'Username']),
            'password.required' =>  __('validation.required', ['attribute' => 'Password']),
            'otp_code.required' => __('validation.required', ['attribute' => 'Kode OTP']),
            'email.required' =>  __('validation.required', ['attribute' => 'Email']),
            'email.exists' =>  __('validation.exists', ['attribute' => 'Email']),
            'password.min' => __('validation.custom.error.default.minCharacter', ['attribute' => 'Password', 'number' => 8]),

            'old_password.required' =>  __('validation.required', ['attribute' => 'Password Lama']),
            'permission_code.required' =>  __('validation.required', ['attribute' => 'Token/Kode Permission']),
            'new_password.required' =>  __('validation.required', ['attribute' => 'Password Baru']),
            'confirm_password.required' => __('validation.required', ['attribute' => 'Konfirmasi Password Baru']),

        ];
    }
}
