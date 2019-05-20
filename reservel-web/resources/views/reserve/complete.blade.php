@extends('layouts.common')
@section('title', '受付完了 - ')
@include('layouts.head')
@section('content')
@section('heading', '受付完了')
@include('layouts.header')
<main>
	<section>
		<div class="comprete_title">受付が完了しました</div>
		<div class="comprete_clinictype">受付区分：{{config('const.CARE_TYPE_NAME')[$careType]['name']}}</div>
		<div class="comprete_number">受付番号：{{$reception_no}}</div>
		<div class="comprete_text">
			ご記入いただいたメールアドレス宛に<br class="br-u600">受付完了メールを送信しました。<br/>
			受付番号が記載されていますので、<br class="br-u600">ご確認ください。
		</div>
		<div class="comprete_back"><a href="{{route('index')}}">受付状況トップ画面に戻る</a></div>
	</section>
</main>
@include('layouts.footer')
@endsection
