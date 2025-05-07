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
        $validationName = $this->route()->getName(); //storeRoleSuperadmin

        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($validationName) {
            case config('constants.route_name.superadmin.organization.upload-image'):
                return [
                    'logo' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.organization.store-info'):
                return [
                    'kode_member' => 'required|unique:master_organisasi,kode_member',
                    'nama_organisasi' => 'required',
                    'provinsi' => 'required',
                    'kabupaten_kota' => 'required',
                    'kecamatan' => 'required',
                    'kelurahan_desa' => 'required',
                    'kode_pos' => 'required',
                    'alamat' => 'required',
                ];

            case config('constants.route_name.superadmin.organization.change-image'):
                return [
                    'logo' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.organization.update'):
                return [
                    'kode_member' => 'unique:master_organisasi,kode_member,except,organisasi_id',
                ];

            case config('constants.route_name.superadmin.organization.store-account'):
                return [
                    'email' => 'required|unique:master_organisasi,email',
                    'domain_admin_url' => 'required',
                    'domain_website_url' => 'required',
                    'password' => 'required',
                    'status' => 'required',
                ];

            case config('constants.route_name.superadmin.organization.assign-module'):
                return [
                    'list_modules' => 'required',

                ];


            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'kode_member.required' =>  __('validation.required', ['attribute' => 'Kode member']),

            'nama_organisasi.required' => __('validation.required', ['attribute' => 'Nama organisasi']),

            'provinsi.required' =>  __('validation.required', ['attribute' => 'Provinsi']),

            'kabupaten_kota.required' => __('validation.required', ['attribute' => 'Kabupaten/Kota']),

            'kecamatan.required' =>  __('validation.required', ['attribute' => 'Kecamatan']),

            'kelurahan_desa.required' => __('validation.required', ['attribute' => 'Kelurahan/Desa']),

            'kode_pos.required' =>  __('validation.required', ['attribute' => 'Kode Pos']),


            'alamat.required' => __('validation.required', ['attribute' => 'Alamat']),


            'email.required' =>  __('validation.required', ['attribute' => 'Email']),


            'password.required' => __('validation.required', ['attribute' => 'Password']),

            'domain_admin_url.required' => __('validation.required', ['attribute' => 'Domain admin URL']),

            'domain_website_url.required' => __('validation.required', ['attribute' => 'Website URL']),

            'logo.required' =>  __('validation.required', ['attribute' => 'Logo']),
            'logo.image' =>  __('validation.image', ['attribute' => 'Logo']),
        ];
    }
}
