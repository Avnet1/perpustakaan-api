<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizeAccessModuleRequest extends FormRequest
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
            case config('constants.route_name.superadmin.organization.assign-module'):
                return [
                    'list_modules' => 'required|array|min:1',
                ];


            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'list_modules.required' =>  __('validation.required', ['attribute' => 'List Modul']),
            'list_modules.array' => __('validation.array', ['attribute' => 'List Modul',]),
            'list_modules.min' => __('validation.min.array', ['attribute' => 'List Modul', 'min' => '1']),
        ];
    }
}
