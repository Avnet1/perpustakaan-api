<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            case config('constants.route_name.superadmin.user.store'):
                return [
                    'role_id' => 'required',
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|min:8',
                ];


            case config('constants.route_name.superadmin.user.upload-photo'):
                return [
                    'photo' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.user.change-photo'):
                return [
                    'photo' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];
            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'role_id.required' =>  __('validation.required', ['attribute' => 'Role']),
            'name.required' =>  __('validation.required', ['attribute' => 'Nama Lengkap']),
            'email.required' =>  __('validation.required', ['attribute' => 'Email']),
            'password.required' =>  __('validation.required', ['attribute' => 'Password']),
            'password.min' => __('validation.custom.error.default.minCharacter', ['attribute' => 'Password', 'number' => 8]),
            'confirm_password.min' => __('validation.custom.error.default.minCharacter', ['attribute' => 'Konfirmasi Ulang Password', 'number' => 8]),
            'email.exists' =>  __('validation.exists', ['attribute' => 'Email']),
            'photo.required' =>  __('validation.required', ['attribute' => 'Photo']),
            'photo.image' =>  __('validation.image', ['attribute' => 'Photo']),
            'photo.max' =>  __('validation.image', ['attribute' => 'Photo', 'max' => '5120']),
            'photo.mimes' =>  __('validation.image', ['attribute' => 'Photo', 'value' => '(jpeg,png,jpg,webp,svg,gif)']),



        ];
    }
}
