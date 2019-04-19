@extends('layouts.common')
@section('title', '製造業者 ログイン  - ')
@include('layouts.head')
@section('content')
<div class="wrapper">
	@include('layouts.header')
	<div class="main_lower">
		<h2>Error<span>システムエラー</span></h2>
	</div>
	<article>
		<div style="margin:50px;text-align:center;font-weight:100;font-size:2em;">{{ $exception->getMessage() }}</div>
	</article>
	@include('layouts.footer')
</div>
@endsection
