<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>リマインドメール</title>
</head>
<body>
 <div style="margin:24px auto;width:90%;">
 <h1 style="padding-bottom:12px;font-size:24px;text-align:center;color:#646464;border-bottom:solid 1px #dcdcdc;">【サンプルクリニック】</h1>
 <p style="padding:8px 24px;">診察の順番が近づいています。</p>
 <p style="padding:8px 24px;">病院へお越し下さい。</p>
 <p style="padding:8px 24px;line-height:2.4em;">
  受付番号：<span style="font-size:36px;">{{$reserve->reception_no}}</span><br>
  受付区分：<span>{{config('const.CARE_TYPE_NAME')[$reserve->care_type]['name']}}</span><br>
  受診される方のお名前：<span>{{$reserve->name}}</span>
 </p>
  <div>
  <div style="padding:8px 24px 32px;line-height:2em;">
    <h2 style="margin-bottom:8px;font-size:16px;">※ご注意ください</h2>
  お呼び出しから1時間が経過してもご来院されない場合、自動的にキャンセルとなります。<br>
  ご来院された方は受付にお声がけください。
  </div>
 </div>
 <div style="margin:0 24px;border:solid 1px #dcdcdc;padding:16px;text-align:center;">
 サンプルクリニック<br>
 <span style="font-size:12px;">〒100-0001 東京都中央区１丁目3−6<br>
 TEL：03-6423-6734</span>
 </div>
 </div>
</body>
</html>