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
	<div class="wrapper">
		<div class="time">{{date('Y/m/d H:i')}} <span>時点の情報です</span></div>
		<? $reserveFirstCnt = count($reserveFirst);$reserveRegularCnt = count($reserveRegular);?>
		<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">{{$reserveFirstCnt + $reserveRegularCnt}}</span><span class="bold">人</span> </div>
		<div class="detail">
			<div class="firstCustomer">
				<div class="label">初診 <span class="count"> {{$reserveFirstCnt}} </span><span class="bold">人</span></div>
				<ul class="number">
				@foreach ($reserveFirst as $reserve)
					<li @if($reserve->status==config('const.RESERVE_STATUS.CALLED'))class="called" @elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE'))class="examine" @endif><span>{{$reserve->reception_no}}</span></li>
				@endforeach
				</ul>
			</div>
			<div class="regularCustomer">
				<div class="label">再診 <span class="count"> {{$reserveRegularCnt}} </span><span class="bold">人</span></div>
				<ul class="number">
				@foreach ($reserveRegular as $reserve)
					<li @if($reserve->status==config('const.RESERVE_STATUS.CALLED'))class="called" @elseif($reserve->status==config('const.RESERVE_STATUS.EXAMINE'))class="examine" @endif><span>{{$reserve->reception_no}}</span></li>
				@endforeach
				</ul>
			</div>
		</div>
		<div class="btns">
      @if($webTicketable==='false')
          @if($setting->inExamineTime())
            <span class="receptionMsg" style="color:red;font-weight:900;">検査中につき診察を一時中断しております。<br>少々お待ちください。</span>
          @else
            <span class="receptionMsg" style="color:red;font-weight:900;">ただいまの時間は受付を行っておりません。</span>
          @endif
      @endif
      <div style="display:flex;justify-content:center;margin-top:1rem;">
        <form method="GET" action="{{route('reserve.create',['diagnosisType'=>1])}}">
          <button class="btn_first"   accesskey="1"@if($webTicketable==='false') disabled @endif>初診受付</button>
        </form>
        <form method="GET" action="{{route('reserve.create',['diagnosisType'=>2])}}">
          <button class="btn_regular" accesskey="2"@if($webTicketable==='false') disabled @endif>再診受付</button>
        </form>
      </div>
    </div>
  </div>
  <div class="notice">※ネットでの受付は午前9:00～11:30　午後16:00～18:30とさせていただきます。<br>※営業終了時刻(午前の部 12:00、午後の部 19:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>
</main>
@include('layouts.footer')
@endsection
