<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class MenuRequest extends FormRequest
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
            case config('constants.route_name.superadmin.menu.storeMenu'):
                return [
                    'modul_id' => 'required',
                    'nama_modul' => 'required',
                    'slug' => 'required',
                    'urutan' => 'required',
                ];

            case config('constants.route_name.superadmin.menu.createIconMenu'):
                return [
                    'icon' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.menu.updateIconMenu'):
                return [
                    'icon' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];


            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [

            'modul_id.required' =>  __('validation.required', ['attribute' => 'Data ID Modul']),
            'nama_modul.required' =>  __('validation.required', ['attribute' => 'Data Nama Modul']),
            'slug.required' => __('validation.required', ['attribute' => 'Data slug']),
            'urutan.required' =>  __('validation.required', ['attribute' => 'Data urutan']),
            'icon.required' =>  __('validation.required', ['attribute' => 'Data Logo/Icon']),
            'icon.image' =>  __('validation.image', ['attribute' => 'Logo/Icon modul']),
        ];
    }
}
