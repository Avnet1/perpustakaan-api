<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterAuthLoginRequest extends FormRequest
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
        return [
            'identity_code' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'identity_code.required' => __('validation.error.default.required', ['attribute' => 'ID Anggota']),
            'password.required' => __('validation.error.default.required', ['attribute' => 'password'])
        ];
    }

}
