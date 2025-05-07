<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SocialMediaRequest extends FormRequest
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
            case config('constants.route_name.superadmin.sosmed.store'):
                return [
                    'identitas_id' => 'required',
                    'nama_sosmed' => 'required',
                    'link_sosmed' => 'required',
                    'logo' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];

            case config('constants.route_name.superadmin.sosmed.update'):
                return [
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];
            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        Log::info('VALIDATION RULE DIPANGGIL');

        return [
            'identitas_id.required' =>  __('validation.required', ['attribute' => 'Data identitas']),
            'nama_sosmed.required' => __('validation.required', ['attribute' => 'Data nama social media']),
            'link_sosmed.required' =>  __('validation.required', ['attribute' => 'Data link social media']),
            'logo.required' =>  __('validation.required', ['attribute' => 'Logo social media']),
            'logo.image' =>  __('validation.image', ['attribute' => 'Logo social media']),
            'logo.max' =>  __('validation.max.file', ['attribute' => 'Logo social media', 'max' => '5120']),
            'logo.mimes' =>  __('validation.mimes', ['attribute' => 'Logo social media', 'value' => '(jpeg,png,jpg,webp,svg,gif)']),
        ];
    }
}
