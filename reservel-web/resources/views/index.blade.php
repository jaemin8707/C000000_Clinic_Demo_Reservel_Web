@extends('layouts.common')
@section('title', '受付状況 - ')
@section('refresh')
<meta http-equiv="refresh" content="120">
@endsection
@include('layouts.head')
@section('content')
@section('heading', '受付状況')
@include('layouts.header')
<main>
	@if(count($notices) > 0)
	<div class="information_box">
		<div class="infomartion_inner">
		 <div class="information_tilte">お知らせ</div>
		 <div class="information_slide">
		 <ul class="slider_news">
			@foreach($notices as $notice)
		 	  <li><span>{{$notice->notice_text}}</span></li>
			@endforeach
		</ul>
		</div>
		</div>
	</div>
	@endif
	<div class="wrapper">
		<div class="time">{{date('Y/m/d H:i')}} <span>時点の情報です</span></div>
		<? $reserveFirstCnt = count($reserveFirst);$reserveRegularCnt = count($reserveRegular); $reserveEtcCnt = count($reserveEtc)?>
		<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">{{$reserveFirstCnt + $reserveRegularCnt + $reserveEtcCnt}}</span><span class="bold">人</span> </div>
		<div class="detail">
			<div class="firstCustomer">
				<div class="label">初診 <span class="count"> {{$reserveFirstCnt}} </span><span class="bold">人</span></div>
				<div class="number">
				  <ul class="number_items">
				    @foreach ($reserveFirst as $reserve)
					   <li @if($reserve->status==config('const.RESERVE_STATUS.CALLED'))class="called" @elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE'))class="examine" @elseif($reserve->status==config('const.RESERVE_STATUS.PAYMENT'))class="payment" @endif><span>{{$reserve->reception_no}}</span></li>
				    @endforeach
					</ul>
				</div>
			</div>
			<div class="regularCustomer">
				<div class="label">再診 <span class="count"> {{$reserveRegularCnt}} </span><span class="bold">人</span></div>
				<div class="number">
					<ul class="number_items">
					@foreach ($reserveRegular as $reserve)
						<li @if($reserve->status==config('const.RESERVE_STATUS.CALLED'))class="called" @elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE'))class="examine" @elseif($reserve->status==config('const.RESERVE_STATUS.PAYMENT'))class="payment" @endif><span>{{$reserve->reception_no}}</span></li>
					@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div class="etcCustomer">
			<div class="label">その他<span class="count">{{$reserveEtcCnt}}</span><span class="bold">人</span></div>
			<div class="number">
				<ul class="number_items">
					@foreach ($reserveEtc as $reserve)
					<li @if($reserve->status==config('const.RESERVE_STATUS.CALLED'))class="called" @elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE'))class="examine" @elseif($reserve->status==config('const.RESERVE_STATUS.PAYMENT'))class="payment" @endif><span>{{$reserve->reception_no}}</span></li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="btns">
      @if($webTicketable==='false')
          <span class="receptionMsg" style="color:red;font-weight:900;">ただいま、受付を行っておりません。</span>
      @endif
      <div style="display:flex;justify-content:center;margin-top:1rem;">
        <form method="GET" action="{{route('reserve.create',['diagnosisType'=>1])}}">
          <button class="btn_first"   accesskey="1"@if($webTicketable==='false') disabled @endif>初診受付</button>
        </form>
        <form method="GET" action="{{route('reserve.create',['diagnosisType'=>2])}}">
          <button class="btn_regular" accesskey="2"@if($webTicketable==='false') disabled @endif>再診受付</button>
				</form>
				<form method="GET" action="{{route('reserve.create',['diagnosisType'=>9])}}">
          <button class="btn_etc" accesskey="9"@if($webTicketable==='false') disabled @endif>その他</button>
        </form>
      </div>
    </div>
  </div>
  <div class="notice">※ネットでの受付は午前00:00～00:00　午後00:00～00:00とさせていただきます。<br>※診療終了時刻(午前の部 00:00、午後の部 00:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>
</main>
@include('layouts.footer')
@endsection
