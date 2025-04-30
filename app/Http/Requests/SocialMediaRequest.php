<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $routeName =  $this->route()->getName(); //storeSosmed_superadmin
        $validationName = "";

        if ($routeName) {
            $validationName =  explode("_", $routeName)[0]; //storeSosmed
        }

        // Menentukan aturan validasi berdasarkan metode HTTP
        switch ($validationName) {
            case 'storeSosmed':
                return [
                    'identitas_id' => 'required',
                    'nama_sosmed' => 'required',
                    'link_sosmed' => 'required',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120'
                ];
            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [

            'identitas_id.required' =>  __('validation.required', ['attribute' => 'Data identitas']),
            'nama_sosmed.required' => __('validation.required', ['attribute' => 'Data nama social media']),
            'link_sosmed.required' =>  __('validation.required', ['attribute' => 'Data link social media']),
            'logo.image' =>  __('validation.image', ['attribute' => 'Logo social media']),
        ];
    }
}
