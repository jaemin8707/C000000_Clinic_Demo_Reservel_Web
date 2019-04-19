<!doctype html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
<title>予約受付完了</title>
</head>

<body>
 <div style="margin:24px auto;width:90%;">
 <h1 style="padding-bottom:12px;font-size:24px;text-align:center;color:#646464;border-bottom:solid 1px #dcdcdc;">【あかつき動物病院】</h1>
 <p style="padding:8px 24px;">診察の予約を受け付けました。</p>
 <p style="padding:8px 24px;line-height:2.4em;">
  受付番号：<span style="font-size:36px;">{{$reserve->reception_no}}</span><br>
  受付区分：<span>{{config('const.CARE_TYPE_NAME')[$reserve->care_type]['name']}}</span><br>
  ペットのお名前：<span>{{$reserve->pet_name}}</span><br>
  飼い主様のお名前：<span>{{$reserve->name}}</span>
 </p>
 <p style="padding:8px 24px;color:#246ee8;">予約をキャンセルされる場合は下記のボタンを押して、キャンセル手続きを行ってください。</p>
 <p style="margin:16px 24px 32px;">
 <a style="display:inline-block;width:240px;height:56px;border-radius:12px;background-color:#246ee8;color:#fff;line-height:56px;text-align:center;text-decoration:none;" href="{{route('reserve.cancel',['cancelToken'=>$reserve->cancel_token])}}" target="_blank">キャンセル手続きに進む</a></p>
  <div>
  <div style="padding:8px 24px 32px;line-height:2em;">
    <h2 style="margin-bottom:8px;font-size:16px;">※ご注意ください</h2>
  お呼び出しから1時間が経過してもご来院されない場合、自動的にキャンセルとなります。<br>
  お呼び出し後、ご来院された方は受付にお声がけください。<br>
  </div>
 </div>
 <div style="margin:0 24px;border:solid 1px #dcdcdc;padding:16px;text-align:center;">
 あかつき動物病院<br>
 <span style="font-size:12px;">〒140-0011 東京都品川区東大井3-16-2<br>グレースビル1階<br>
 TEL：03-6404-6049</span>
 </div>
 </div>
</body>
</html>