<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score'   => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:255',
        ];
    }

 
    public function attributes(): array
    {
        return [
            'score'   => '評価',
            'comment' => 'コメント',
        ];
    }
}
