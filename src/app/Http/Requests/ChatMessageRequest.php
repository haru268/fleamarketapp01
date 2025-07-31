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

        'body'  => ['nullable', 'string', 'max:400'],


        'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
    ];
}


    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max'      => '本文は400文字以内で入力してください',
            'image.mimes'   => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
