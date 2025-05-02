<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
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
            case config('constants.route_name.superadmin.grade.store'):
                return [
                    'nama_jenjang' => 'required',
                    'urutan' => 'required',
                ];

            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'nama_jenjang.required' =>  __('validation.required', ['attribute' => 'Nama Jenjang']),
            'urutan.required' => __('validation.required', ['attribute' => 'No. Urut']),
        ];
    }
}
