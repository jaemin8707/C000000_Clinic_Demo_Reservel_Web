@section('head')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','')管理画面 - {{env('HOSPITAL_NAME','')}} - リザベル</title>
<meta name="description" content="{{env('HOSPITAL_NAME','')}}の現在の混雑状況です。">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">
@yield('refresh')
@endsection