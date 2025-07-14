<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatMessageRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

 
    public function rules(): array
    {
        return [
            'body'  => ['required', 'string', 'max:400'],
            'image' => ['nullable', 'image', 'mimes:png,jpeg,jpg'],
        ];
    }

    
    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max'      => '本文は400文字以内で入力してください',
            'image.image'   => '画像ファイルをアップロードしてください',
            'image.mimes'   => '画像は .png または .jpeg 形式でアップロードしてください',
        ];
    }
}
