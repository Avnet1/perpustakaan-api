<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinsiRequest extends FormRequest
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
            case 'provinsi':
                if ($this->method() == 'POST') {
                    return [
                        'nama_provinsi' => 'required',
                        'kode_provinsi' => 'required',
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
            'nama_provinsi.required' =>  __('validation.required', ['attribute' => 'Nama Provinsi']),
            'kode_provinsi.required' => __('validation.required', ['attribute' => 'Kode Provinsi']),
            'kode_dikti.required' =>  __('validation.required', ['attribute' => 'Kode Dikti']),
        ];
    }
}
