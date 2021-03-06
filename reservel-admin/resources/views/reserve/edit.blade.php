@extends('layouts.common')
@section('title', '受付 - ')
@include('layouts.head')
@section('content')
@section('heading', '受付情報編集')
@include('layouts.header')
<main>
	<div class="wrapper960">
		<section>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
			<form action="{{route('reserve.update',['reserve'=>$reserve->id])}}" method="POST">
				{{csrf_field()}}
				<input type="hidden" name="_method" value="PUT">
				<dl class="form_items">
					<dt><span>受付番号</span></dt>
          <dd>{{$reserve->reception_no}}</dd>
					</dd>
					<dt><label for="status">ステータス</label></dt>
					<dd>
						<select class="status" id="status" name="status">
							<option value="{{config('const.RESERVE_STATUS.WAITING')}}" @if($reserve->status==config('const.RESERVE_STATUS.WAITING'))selected @endif>待ち</option>
							<option value="{{config('const.RESERVE_STATUS.CALLED')}}"  @if($reserve->status==config('const.RESERVE_STATUS.CALLED'))selected @endif>呼び出し済み</option>
							<option value="{{config('const.RESERVE_STATUS.EXAMINE')}}" @if($reserve->status==config('const.RESERVE_STATUS.EXAMINE'))selected @endif>診察中</option>
							<option value="{{config('const.RESERVE_STATUS.PAYMENT')}}" @if($reserve->status==config('const.RESERVE_STATUS.PAYMENT'))selected @endif>会計</option>
							<option value="{{config('const.RESERVE_STATUS.DONE')}}"    @if($reserve->status==config('const.RESERVE_STATUS.DONE'))selected @endif>完了</option>
							<option value="{{config('const.RESERVE_STATUS.CANCEL_BY_HOSPITAL')}}"  @if($reserve->status==config('const.RESERVE_STATUS.CANCEL_BY_HOSPITAL'))selected @endif>キャンセル</option>
						</select>
						@if($reserve->status==config('const.RESERVE_STATUS.CALLED')) <div>（呼出時刻<span>{{$reserve->formattedCallTime()}}</span>）</div> @endif
					</dd>
					<dt><span>受付場所</span></dt>
					<dd>{{config('const.PLACE_NAME')[$reserve->place]}}</dd>
					<dt><span>受付区分</span></dt>
					<dd>{{config('const.CARE_TYPE_NAME')[$reserve->care_type]['name']}}</dd>
					<dt><label for="patient_no">診察券番号</label></dt>
					<dd><input type="text" id="patient_no" name="patient_no" value="{{old('medical_card_no', $reserve->medical_card_no)}}" /></dd>
					<dt><label for="name">受診される方のお名前</label></dt>
					<dd><input type="text" id="name" name="name" value="{{old('name',$reserve->name)}}" /></dd>
					<dt><label for="age">年齢</label></dt>
					<dd><input type="text" style="width:5rem" id="age" name="age" value="{{$reserve->age}}" /></dd>
					<dt><label for="purpose">性別</label></dt>
					<dd id="new_line">
							@foreach (config('const.GENDER') as $genderKey => $gender)
						<label class="form_item_group">
							<input type="radio" id="gender_{{$genderKey}}" name="gender" value="{{$genderKey}}" {{ old("gender") == $genderKey ? 'checked="checked"' : '' }}/>{{$gender}}
						</label>
						@endforeach
					</dd>
					<dt><label for="email">メールアドレス</label></dt>
					<dd><input type="email" id="email" name="email" value="{{old('email', $reserve->email)}}" /></dd>
					<dt><label for="tel">電話番号</label></dt>
					<dd><input type="tel" id="tel" name="tel" value="{{$reserve->tel}}" /></dd>

					<dt><label for="pet_symptom">症状など</label></dt>
					<dd><textarea id="pet_symptom" name="pet_symptom" rows="5">{{old('conditions', $reserve->conditions)}}</textarea></dd>
				</dl>
				<div class="console">
					<input type="hidden" name="scroll" value="{{$scroll}}">
					<input type="hidden" name="id" value="{{$reserve->id}}">
					<a href="{{route('index',['scroll'=>$scroll])}}" class="btn_cancel">戻　る</a>
					<button class="btn_execution">更　新</button>
				</div>
			</form>
		</section>
	</div>
</main>
@include('layouts.footer')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
$(function(){
	$('input[type=number]').keypress(function(e){
			var st = String.fromCharCode(e.which);
			if ("0123456789".indexOf(st,0) < 0) { 
					return false;
			}
			return true;
	});
	$('input[type=tel]').keypress(function(e){
			var st = String.fromCharCode(e.which);
			if ("0123456789()-".indexOf(st,0) < 0) { 
					return false;
			}
			return true;
	});
	$('.status').change(function() {
	 var val = $(this).val();
	 var count =$(this).next()
	 var timer = $(this).next().children('span');
	 if(val == 20){
		$(count).fadeIn(200);
		$(timer).text('10');
	 } else {
		$(count).hide();
		$(target).text('--');
	 }
		}); 
});
function aTagGetScroll(scroll) {
	event.target.href += "?scroll=" +scroll;
}
</script>
@endsection
