<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class IdentityRequest extends FormRequest
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
            case 'identity':
                if ($this->method() == 'POST' && !$this->route('identitas_id')) {
                    return [
                        'nama_perusahaan' => 'required',
                        'kota' => 'required',
                        'email' => 'required',
                        'telepon' => 'required',
                        'website' => 'required',
                        'alamat' => 'required',
                        'footer' => 'required',
                        'deskripsi' => 'required',
                        'privacy_policy' => 'required',
                        'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
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

            'nama_perusahaan.required' =>  __('validation.required', ['attribute' => 'Data Nama Perusahaan']),
            'kota.required' => __('validation.required', ['attribute' => 'Data Kota']),
            'email.required' =>  __('validation.required', ['attribute' => 'Data Email']),
            'telepon.required' => __('validation.required', ['attribute' => 'Data No. Telepon']),
            'website.required' =>  __('validation.required', ['attribute' => 'Data Website']),
            'alamat.required' => __('validation.required', ['attribute' => 'Data Alamat']),
            'footer.required' =>  __('validation.required', ['attribute' => 'Data Footer']),
            'deskripsi.required' => __('validation.required', ['attribute' => 'Data Deskripsi']),
            'privacy_policy.required' =>  __('validation.required', ['attribute' => 'Data Privacy Policy']),
            'photo.image' =>  __('validation.image', ['attribute' => 'Logo Perusahaan']),
        ];
    }
}
