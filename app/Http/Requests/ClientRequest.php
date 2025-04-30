<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            case 'clients/info':
                if ($this->method() == 'POST') {
                    return [
                        'client_code' => 'required',
                        'client_name' => 'required',
                        'client_phone' => 'required',
                        'organisasi_id' => 'required',
                        'client_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                    ];
                }

            case 'clients/account':
                if ($this->method() == 'PUT') {
                    return [
                        'username' => 'required',
                        'password' => 'required',
                    ];
                }

            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'client_code.required' =>  __('validation.required', ['attribute' => 'ID Pelanggan']),
            'client_name.required' => __('validation.required', ['attribute' => 'Nama Pelanggan']),
            'client_phone.required' =>  __('validation.required', ['attribute' => 'No. Telepon']),
            'organisasi_id.required' => __('validation.required', ['attribute' => 'Organisasi']),
            'username.required' =>  __('validation.required', ['attribute' => 'Username']),
            'password.required' => __('validation.required', ['attribute' => 'Password']),
            'client_photo.image' =>  __('validation.image', ['attribute' => 'Photo']),
        ];
    }
}
