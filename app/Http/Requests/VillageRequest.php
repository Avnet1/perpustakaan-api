<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VillageRequest extends FormRequest
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
            case config('constants.route_name.superadmin.village.store'):
                return [
                    'nama_kelurahan' => 'required',
                    'kode_kelurahan' => 'required',
                    'kode_dikti' => 'required',
                    'kecamatan_id' => 'required',
                    'provinsi_id' => 'required',
                    'kabupaten_kota_id' => 'required',
                ];

            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'nama_kelurahan.required' =>  __('validation.required', ['attribute' => 'Nama Kelurahan']),
            'kode_kelurahan.required' => __('validation.required', ['attribute' => 'Kode Kelurahan']),
            'kode_dikti.required' => __('validation.required', ['attribute' => 'Kode Dikti']),
            'kecamatan_id.required' =>  __('validation.required', ['attribute' => 'Kecamatan']),
            'provinsi_id.required' =>  __('validation.required', ['attribute' => 'Provinsi']),
            'kabupaten_kota_id.required' =>  __('validation.required', ['attribute' => 'Kabupaten/Kota']),
        ];
    }
}
