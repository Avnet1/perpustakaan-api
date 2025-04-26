<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipeGMDRequest extends FormRequest
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
        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($this->method()) {
            case 'POST': // Tambah Data GMD
                return [
                    'kode' => 'required|string|max:150|unique:master_tipe_gmd,kode',
                    'nama' => 'required|string|max:255',
                ];

            case 'PUT': // Update Data GMD
            case 'PATCH':
                $id = $this->route('gmd_id');
                return [
                    'kode' => 'required|string|max:150|unique:master_tipe_gmd,kode,' . $id,
                    'nama' => 'required|string|max:255',
                ];

            case 'GET':
                return [
                    'gmd_id' => 'required|exists:master_tipe_gmd,gmd_id',
                ];

            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'kode.required' =>  __('validation.error.default.required', ['attribute' => 'Kode GMD']),
            'kode.unique' => __('validation.error.default.existed', ['attribute' => 'Kode GMD']),
            'nama.required' =>  __('validation.error.default.required', ['attribute' => 'Nama GMD']),
            'gmd_id.required' =>  __('validation.error.default.required', ['attribute' => 'ID GMD']),
            'gmd_id.exists' => __('validation.error.default.notFound', ['attribute' => 'ID GMD']),
        ];
    }
}
