<?php

return [
    // ----- 共通メッセージ -----
    'required'  => ':attribute を入力してください。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'confirmed' => ':attribute と一致しません。',
    'unique'    => ':attribute は既に登録されています。',

    // ----- 属性名を日本語に置き換え -----
    'attributes' => [
        'name'                  => 'お名前',
        'email'                 => 'メールアドレス',
        'password'              => 'パスワード',
        'password_confirmation' => 'パスワード（確認用）',
    ],
];
