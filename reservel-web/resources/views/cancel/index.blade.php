@extends('layouts.common')
@section('title', '予約キャンセル - ')
@include('layouts.head')
@section('content')
@section('heading', '予約キャンセル')
@include('layouts.header')
<main>
  <section>
    <div class="comprete_title">予約キャンセル</div>
    <div class="comprete_clinictype">キャンセルする受付番号：{{$reserve->reception_no}}</div>
    <div class="comprete_text">
        
    </div>
    <div class="console" style="margin-bottom:5rem;display:flex;justify-content:center;">
      <form id="delReserve" method="post" action="{{route('reserve.cancel_complete')}}" >
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$reserve->id}}" />
        <input type="hidden" name="cancel_token"  value="{{$reserve->cancel_token}}" />
        <button id="btnDelete" type="submit" class="btn_execution">キャンセルする</button>
      </form>
    </div>
		<div class="comprete_text">キャンセルしない場合はブラウザを閉じて下さい。</div>
  </section>
</main>
@include('layouts.footer')
<style>
body div.popup_modals .modal_buttons .btn.btn_pmry{width:100px;background-color:#e74c3c;}
body div.popup_modals .modal_buttons .btn.btn_sdry{width:100px;background-color:gray;}
body div.popup_modals .modal_buttons.right{text-align:center;}
</style>
<script src="{{asset('/js/jquery.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('/css/popupmodal.css')}}" />
<script src="{{asset('/js/popupmodal-min.js')}}"></script>
<script>
$(function(){
  $("#btnDelete").click(function(e){
      popup.confirm(
        { 
          content: '予約番号：{{$reserve->reception_no}}をキャンセルします。よろしいですか？',
          default_btns : {
            ok : 'はい',
            cancel : 'いいえ'
          }
         ,btn_align : 'right' 
         ,modal_size : 350 
        },
        function(config){
          var e = config.e;
          if(config.proceed){
              $('#delReserve').submit();
          }
        });
      return false;
 });
});
</script>
@endsection
