<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.image' => 'ファイルは画像でなければなりません。',
            'profile_image.mimes' => '画像は.jpegまたは.pngの拡張子である必要があります。',
            'profile_image.max' => '画像サイズは2MB未満である必要があります。',
        ];
    }
}
