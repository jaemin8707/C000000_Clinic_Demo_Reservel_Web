<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReserveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reserve)
    {
        // 引数で受け取ったデータを変数にセット
        $this->reserve = $reserve;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this
          ->subject(config('const.RESERVE_MAIL_TITLE')."受付番号：".$this->reserve->reception_no) // メールタイトル
          ->view('email.mail') // どのテンプレートを呼び出すか
          ->with(['reserve' => $this->reserve]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
