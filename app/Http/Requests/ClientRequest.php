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
            case 'clients':
                if ($this->method() == 'POST') {
                    return [
                        'client_code' => 'required',
                        'client_name' => 'required',
                        'client_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
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
            'client_code.required' =>  __('validation.required', ['attribute' => 'ID Client']),
            'client_name.required' => __('validation.required', ['attribute' => 'Nama Lengkap']),
            'client_photo.image' =>  __('validation.image', ['attribute' => 'Photo']),
        ];
    }
}
