<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;

class FixedDemoSeeder extends Seeder
{
    public function run(): void
    {
       
        $seller1 = User::create([
            'name'     => 'Seller One',
            'email'    => 'seller1@example.com',
            'password' => Hash::make('password'),
        ]);

        $seller2 = User::create([
            'name'     => 'Seller Two',
            'email'    => 'seller2@example.com',
            'password' => Hash::make('password'),
        ]);

        $lonely  = User::create([
            'name'     => 'Lonely User',
            'email'    => 'lonely@example.com',
            'password' => Hash::make('password'),
        ]);

        /* ---------- ② 商品10件 ---------- */
        $products = [
            // seller1 が CO01〜CO05 を出品
            ['腕時計',15000,'スタイリッシュなデザインのメンズ腕時計','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg','良好',$seller1->id],
            ['HDD',5000,'高速で信頼性の高いハードディスク','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg','目立った傷や汚れなし',$seller1->id],
            ['玉ねぎ3束',300,'新鮮な玉ねぎ3束のセット','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg','やや傷や汚れあり',$seller1->id],
            ['革靴',4000,'クラシックなデザインの革靴','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg','状態が悪い',$seller1->id],
            ['ノートPC',45000,'高性能なノートパソコン','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg','良好',$seller1->id],

            // seller2 が CO06〜CO10 を出品
            ['マイク',8000,'高音質のレコーディング用マイク','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg','目立った傷や汚れなし',$seller2->id],
            ['ショルダーバッグ',3500,'おしゃれなショルダーバッグ','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg','やや傷や汚れあり',$seller2->id],
            ['タンブラー',500,'使いやすいタンブラー','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg','状態が悪い',$seller2->id],
            ['コーヒーミル',4000,'手動のコーヒーミル','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg','良好',$seller2->id],
            ['メイクセット',2500,'便利なメイクアップセット','https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg','目立った傷や汚れなし',$seller2->id],
        ];

        foreach ($products as [$name,$price,$desc,$url,$cond,$uid]) {
            Product::create([
                'name'        => $name,
                'description' => $desc,
                'price'       => $price,
                'image'       => $url,
                'condition'   => $cond,
                'user_id'     => $uid,
                'is_recommended'  => true,
            ]);
        }
    }
}
