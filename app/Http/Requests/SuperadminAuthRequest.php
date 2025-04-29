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
        $routeUri = $route ? $route->uri() : '';
        $routeUri = preg_replace('/^api\/v1\/superadmin\//', '', $routeUri);

        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($routeUri) {
            case 'auth/login':
                return [
                    'username' => 'required',
                    'password' => 'required|min:8',
                ];

            case 'auth/forgot-password':
                return [
                    'email' => 'required|exists:users,email',
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
            'email.required' =>  __('validation.required', ['attribute' => 'Email']),
            'email.exists' =>  __('validation.exists', ['attribute' => 'Email']),
            'password.min' => __('validation.custom.error.default.minCharacter', ['attribute' => 'Password', 'number' => 8]),

        ];
    }
}
