<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubdistrictRequest extends FormRequest
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
            case 'sub-districts':
                if ($this->method() == 'POST') {
                    return [
                        'provinsi_id' => 'required',
                        'kabupaten_kota_id' => 'required',
                        'nama_kecamatan' => 'required',
                        'kode_kecamatan' => 'required',
                        'kode_dikti' => 'required',
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
            'nama_kecamatan.required' =>  __('validation.required', ['attribute' => 'Nama Kecamatan']),
            'kode_kecamatan.required' => __('validation.required', ['attribute' => 'Kode Kecamatan']),
            'kode_dikti.required' => __('validation.required', ['attribute' => 'Kode Dikti']),
            'kabupaten_kota_id.required' =>  __('validation.required', ['attribute' => 'Kabupaten/Kota']),
            'provinsi_id.required' =>  __('validation.required', ['attribute' => 'Provinsi']),
        ];
    }
}
