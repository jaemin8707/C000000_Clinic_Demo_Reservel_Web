@extends('layouts.common')
@section('title', '予約キャンセル完了 - ')
@include('layouts.head')
@section('content')
@section('heading', '予約キャンセル完了')
@include('layouts.header')
<main>
	<section>
		<div class="comprete_title">予約をキャンセルしました。</div>
		<div class="comprete_text">
			再予約を希望の方は<br class="br-u600">新しく予約を行ってください。
		</div>
		<div class="comprete_back"><a href="{{route('index')}}">受付状況トップ画面に戻る</a></div>
	</section>
</main>
@include('layouts.footer')
@endsection
