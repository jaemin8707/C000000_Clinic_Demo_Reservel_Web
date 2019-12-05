@section('head')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
<title>@yield('title',''){{env('HOSPITAL_NAME','')}} - リザベル</title>
<meta name="description" content="{{env('HOSPITAL_NAME','')}}の現在の混雑状況です。">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex" />
<link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/slick.css')}}">
<script src="{{asset('/js/jquery.min.js')}}" type="text/javascript" charset="utf-8"></script>
<script src="{{asset('/js/slick.min.js')}}" type="text/javascript" charset="utf-8"></script>
@yield('refresh')
@endsection