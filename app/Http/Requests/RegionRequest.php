<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionRequest extends FormRequest
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
            case 'regions':
                if ($this->method() == 'POST') {
                    return [
                        'nama_kabupaten_kota' => 'required',
                        'kode_kabupaten_kota' => 'required',
                        'status_administrasi' => 'required',
                        'kode_dikti' => 'required',
                        'provinsi_id' => 'required'
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
            'nama_kabupaten_kota.required' =>  __('validation.required', ['attribute' => 'Nama Kabupaten/Kota']),
            'kode_kabupaten_kota.required' => __('validation.required', ['attribute' => 'Kode Kabupaten/Kota']),
            'status_administrasi.required' =>  __('validation.required', ['attribute' => 'Status Administrasi']),
            'kode_dikti.required' => __('validation.required', ['attribute' => 'Kode Dikti']),
            'provinsi_id.required' =>  __('validation.required', ['attribute' => 'Provinsi']),
        ];
    }
}
