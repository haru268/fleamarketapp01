@component('mail::message')
# 取引完了のお知らせ

{{ $buyerName }} さんとの取引が完了しました。  
商品：**{{ $productName }}**

@component('mail::button', ['url' => url('/trades/'.$trade->id)])
チャットを確認する
@endcomponent

ご利用ありがとうございます。  
{{ config('app.name') }}
@endcomponent
