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
        $route = $this->route();
        $routeName =  $this->route()->getName(); //authSuperadminLogin_superadmin
        $validationName = "";

        if ($routeName) {
            $validationName =  explode("_", $routeName)[0]; //storeSosmed
        }


        // $routeUri = $route ? $route->uri() : '';
        // $routeUri = preg_replace('/^api\/v1\/superadmin\//', '', $routeUri);

        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($validationName) {
            case 'authSuperadminLogin':
                return [
                    'email' => 'required',
                    'password' => 'required|min:8',
                ];

            case 'authSuperadminForgotPassword':
                return [
                    'email' => 'required',
                ];

            case 'authSuperadminVerifiedOtp':
                return [
                    'email' => 'required',
                    'otp_code' => 'required'
                ];

            case 'authSuperadminResetPassword':
                return [
                    'permission_code' => 'required',
                    'new_password' => 'required',
                    'confirm_password' => 'required'
                ];

            case 'authSuperadminChangePassword':
                return [
                    'old_password' => 'required',
                    'new_password' => 'required',
                    'confirm_password' => 'required'
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
