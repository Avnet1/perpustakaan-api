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
        $route = $this->route();
        $routeUri = $route ? $route->uri() : '';
        $routeUri = preg_replace('/^api\/v1\/superadmin\//', '', $routeUri);

        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($routeUri) {
            case 'grades':
                if ($this->method() == 'POST') {
                    return [
                        'nama_jenjang' => 'required',
                        'urutan' => 'required',
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
            'nama_jenjang.required' =>  __('validation.required', ['attribute' => 'Nama Jenjang']),
            'urutan.required' => __('validation.required', ['attribute' => 'Urutan']),
        ];
    }
}
