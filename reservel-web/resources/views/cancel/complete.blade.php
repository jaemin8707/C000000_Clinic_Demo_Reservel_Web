@extends('layouts.common')
@section('title', '受付キャンセル完了 - ')
@include('layouts.head')
@section('content')
@section('heading', '受付キャンセル完了')
@include('layouts.header')
<main>
	<section>
		<div class="comprete_title">受付をキャンセルしました。</div>
		<div class="comprete_text">
			再受付を希望の方は<br class="br-u600">新しく受付を行ってください。
		</div>
		<div class="comprete_back"><a href="{{route('index')}}">受付状況トップ画面に戻る</a></div>
	</section>
</main>
@include('layouts.footer')
@endsection
