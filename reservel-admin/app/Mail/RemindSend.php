<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemindSend extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reserve)
    {
        //
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
          ->subject(config('const.REMIND_MAIL_TITLE')) // メールタイトル
          ->view('email.mail') // どのテンプレートを呼び出すか
          ->with(['reserve' => $this->reserve]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
