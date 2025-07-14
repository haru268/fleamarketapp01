<?php

return [

    'required'  => ':attribute を入力してください。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'confirmed' => ':attribute と一致しません。',
    'unique'    => ':attribute は既に登録されています。',


    'attributes' => [
        'name'                  => 'お名前',
        'email'                 => 'メールアドレス',
        'password'              => 'パスワード',
        'password_confirmation' => 'パスワード（確認用）',
    ],
];
