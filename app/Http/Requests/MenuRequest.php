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
            case config('constants.route_name.superadmin.menu.store'):
                return [
                    'modul_id' => 'required',
                    'nama_menu' => 'required',
                    'slug' => 'required',
                    'urutan' => 'required',
                ];

            case config('constants.route_name.superadmin.menu.uploadIcon'):
                return [
                    'icon' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.menu.changeIcon'):
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
            'modul_id.required' =>  __('validation.required', ['attribute' => 'ID Modul']),
            'nama_menu.required' =>  __('validation.required', ['attribute' => 'Nama Menu']),
            'slug.required' => __('validation.required', ['attribute' => 'Slug']),
            'urutan.required' =>  __('validation.required', ['attribute' => 'Urutan']),
            'icon.required' =>  __('validation.required', ['attribute' => 'Logo/icon']),
            'icon.image' =>  __('validation.image', ['attribute' => 'Logo/Icon']),
            'icon.max' =>  __('validation.max.file', ['attribute' => 'Logo/Icon', 'max' => '5120']),
            'icon.mimes' =>  __('validation.mimes', ['attribute' => 'Logo/Icon', 'value' => '(jpeg,png,jpg,webp,svg,gif)']),
        ];
    }
}
