@extends('layouts.common')
@section('title', '受付状況 - ')
@include('layouts.head')
@section('content')
@section('heading', '受付状況')
@include('layouts.header')

<div class="title">
	受付システムからのメールが届かない（ドメイン指定受信の設定方法）
</div>

<main class="data_main">
 <p class="data_main_comment">迷惑メールの設定によっては、受付システムからのメールが届かない場合があります。<br>
  メールが届かない場合は、受付システムからのメールが受信できるようにドメイン指定受信で「@otake-reservel.jp」を許可するように設定してください。</p>

	
    <section class="data_inner">	
	  <div class="data_inner_title">■Yahoo!メールをお使いの方</div>
	  <p class="data_inner_comment">下記のページを参照いただき、フィルターを設定してください。</p>
	  <a class="data_inner_a" href="https://www.yahoo-help.jp/app/answers/detail/a_id/47921/p/622#1">Yahoo!メールドメイン指定受信設定方法</a>
	 </section>
		
	 <section class="data_inner">	
	  <div class="data_inner_title">■Outlook on the webをお使いの方</div>
	  <p class="data_inner_comment">
         下記のページを参照いただき、フィルター処理を行ってください。</p>
	  <a class="data_inner_a" href="https://support.office.com/ja-jp/article/web-%E7%89%88-outlook-%E3%81%A7%E8%BF%B7%E6%83%91%E3%83%A1%E3%83%BC%E3%83%AB%E3%81%8A%E3%82%88%E3%81%B3%E3%82%B9%E3%83%91%E3%83%A0%E3%82%92%E3%83%95%E3%82%A3%E3%83%AB%E3%82%BF%E3%83%BC%E5%87%A6%E7%90%86%E3%81%99%E3%82%8B-db786e79-54e2-40cc-904f-d89d57b7f41d">Outlook on the webドメイン指定受信設定方法</a>
		  </section>
		 
	  <section class="data_inner">
	  <div class="data_inner_title">■Gmailをお使いの方</div>
	  <p class="data_inner_comment">
         下記のページを参照いただき、メールのフィルタルールの作成をしてください。</p>
	  <a class="data_inner_a" href="https://support.google.com/mail/answer/6579?hl=ja">Gmailドメイン指定受信設定方法</a>
	   </section>
		   
	  <section class="data_inner">
	  <div class="data_inner_title">■docomoのメールアドレスをお使いの方</div>
	  <p class="data_inner_comment">下記のページの「受信リスト設定」を参照いただき、「@otake-reservel.jp」を受信するドメインに追加してください。</p>
	  <a href="https://www.nttdocomo.co.jp/info/spam_mail/spmode/domain/">docomoドメイン指定受信設定方法</a>
	  </section>
		   
	  <section class="data_inner">
	  <div class="data_inner_title">■auのメールアドレスをお使いの方</div>
	  <p class="data_inner_comment">下記のページを参照いただき、受信リスト設定で「@otake-reservel.jp」を追加してください。</p>
	  <a href="https://www.au.com/support/service/mobile/trouble/mail/email/filter/detail/domain/">auドメイン指定受信設定方法</a>
	  </section>
		   
	  <section class="data_inner">
	  <div class="data_inner_title">■SoftBankのメールアドレス（S!メール（MMS）とEメール（i））をお使いの方</div>
	  <p class="data_inner_comment">下記のページを参照いただき、メール設定で「@otake-reservel.jp」を許可するメールとして登録してください。</p>
	  <a href="https://www.softbank.jp/mobile/support/iphone/antispam/email_i/white/">SoftBankドメイン指定受信設定方法</a>
	  </section>
	
	</main>
	
</div>	
</body>


@include('layouts.footer')
@endsection