<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ModuleRequest extends FormRequest
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
            case config('constants.route_name.superadmin.module.store'):
                return [
                    'nama_modul' => 'required',
                    'slug' => 'required',
                    'urutan' => 'required',
                ];

            case config('constants.route_name.superadmin.module.uploadIcon'):
                return [
                    'icon' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.module.changeIcon'):
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

            'nama_modul.required' =>  __('validation.required', ['attribute' => 'Data nama modul']),
            'slug.required' => __('validation.required', ['attribute' => 'Data slug']),
            'urutan.required' =>  __('validation.required', ['attribute' => 'Data urutan']),
            'icon.required' =>  __('validation.required', ['attribute' => 'Data logo/icon']),
            'icon.image' =>  __('validation.image', ['attribute' => 'Logo/Icon modul']),
        ];
    }
}
