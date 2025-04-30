<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
            case 'organizations/info':
                if ($this->method() == 'POST') {
                    return [
                        'universitas_id' => 'required',
                        'provinsi_id' => 'required',
                        'kabupaten_kota_id' => 'required',
                        'kecamatan_id' => 'required',
                        'kelurahan_id' => 'required',
                        'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                    ];
                }

            case 'organizations/account':
                if ($this->method() == 'PUT') {
                    return [
                        'email' => 'required',
                        'domain_admin_url' => 'required',
                        'domain_website_url' => 'required',
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
            'universitas_id.required' =>  __('validation.required', ['attribute' => 'Data Universitas']),
            'provinsi_id.required' => __('validation.required', ['attribute' => 'Data Provinsi']),
            'kabupaten_kota_id.required' =>  __('validation.required', ['attribute' => 'Data Kabupaten/Kota']),
            'kecamatan_id.required' => __('validation.required', ['attribute' => 'Data Kecamatan']),
            'kelurahan_id.required' =>  __('validation.required', ['attribute' => 'Data Kelurahna']),
            'email.required' => __('validation.required', ['attribute' => 'Data Email']),
            'domain_admin_url.required' =>  __('validation.required', ['attribute' => 'Data URL Admin Perpustakaan']),
            'domain_website_url.required' => __('validation.required', ['attribute' => 'Data URL Website Perpustakaan']),
            'logo.image' =>  __('validation.image', ['attribute' => 'Logo Organisasi']),
        ];
    }
}
