<!doctype html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
<title>パスワードリセットのリクエストを受け付けました</title>
</head>
<body>
 <div style="margin:24px auto;width:90%;">
  <h1 style="padding-bottom:12px;font-size:24px;text-align:center;color:#646464;border-bottom:solid 1px #dcdcdc;">管理画面のパスワードリセットのリクエストを受け付けました</h1>
  <p style="padding:8px 24px;">このメールはパスワードリセットのご依頼を受けたため自動的に送信しています。</p>
  <p style="padding:8px 24px;color:#246ee8;">パスワードをリセットされる場合は下記のボタンを押して、パスワード変更手続きを行ってください。</p>
  <p style="margin:16px 24px 32px;">
   <a style="display:inline-block;width:240px;height:56px;border-radius:12px;background-color:#246ee8;color:#fff;line-height:56px;text-align:center;text-decoration:none;" href="{{route('password.reset',['token'=>$token])}}" target="_blank">パスワードリセット手続きに進む</a>
  </p>
  <div>
   <div style="padding:8px 24px 32px;line-height:2em;">
    <h2 style="margin-bottom:8px;font-size:16px;">ご注意ください</h2>
    <ul>
     <li>このパスワードリセットのリンクが60分に期限切れになります。</li>
     <li>今回、パスワードのリセットをされない場合は、それ以上のアクションは必要ありません。</li>
     <li>上記の「パスワードリセット手続きに進む」ボタンをクリックして問題がある場合は、<br>
         お手数ですが「{{route('password.reset',['token'=>$token])}}」をコピーして、Webブラウザで開いて下さい。</li>
    <ul>
   </div>


  </div>
  <div style="margin:0 24px;border:solid 1px #dcdcdc;padding:16px;text-align:center;">
   Reservel System
  </div>
 </div>
</body>
</html>