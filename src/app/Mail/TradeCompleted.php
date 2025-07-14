<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Trade;   

class TradeCompleted extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Models\Trade */
    public $trade;

    /**
     * 取引情報を受け取ってプロパティへ保持
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    /**
     * メールを組み立てる
     */
    public function build()
    {
        return $this
            ->subject('【COACHTECHフリマ】取引が完了しました')
            ->markdown('emails.trade_completed')
            ->with([
                'productName' => $this->trade->product->name,
                'buyerName'   => $this->trade->buyer->name,
            ]);
    }
}
