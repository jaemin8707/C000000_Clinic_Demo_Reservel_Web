@extends('layouts.common')
@section('title', '予約受付 - ')
@include('layouts.head')
@section('content')
@section('heading', '予約受付')
@include('layouts.header')
<?php $careTypeName = config('const.CARE_TYPE_NAME')[$careType]; ?>
<main>
	<div class="wrapper">
		<section>
			<div class="clinicType {{$careTypeName['class_name']}}">{{$careTypeName['name']}}予約申し込み</div>
		</section>
		@if ($errors->any())
		<div class="alert alert-danger" style="color:red;">
			<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
			</ul>
		</div>
		@endif
		<section>
			<form action="{{route('reserve.confirm')}}" method="POST">
				{{csrf_field()}}
				<div class="type"><span>受付区分</span>：<span>{{$careTypeName['name']}}</span></div>
				<input type="hidden" name="careType" value="{{$careType}}" />
				<dl class="form_items">
					@if($careType==config('const.CARE_TYPE.REGULAR'))
					<dt><label for="patient_no">診察券番号</label></dt>
					<dd><input type="text" id="patient_no" name="patient_no" maxlength="4" placeholder="例）100" /></dd>
					@endif
					<dt class="required"><label for="name">お名前</label></dt>
					<dd><input type="text" id="name" name="name" value="{{old('name')}}" placeholder="例）動物　太郎" required /></dd>
					<dt class="required"><label for="email">メールアドレス</label></dt>
					<dd><input type="email" id="email" name="email" value="{{old('email')}}" placeholder="例）taro.animal@gmail.com" required /></dd>
					<dt class="required"><label for="tel">電話番号</label></dt>
					<dd><input type="tel" id="tel" name="tel" value="{{old('tel')}}" placeholder="例）0331234567" required /></dd>
					<dt class="required"><label for="pet_type">ペットの種類</label></dt>
					<dd>
					@foreach (config('const.PET_TYPE_NAME') as $petKey => $petName)
						<input type="checkbox" value="{{$petKey}}" id="pet_type_{{$petKey}}" name="pet_type[]"  {{ is_array(old("pet_type")) && in_array("$petKey", old("pet_type"), true)? 'checked="checked"' : '' }}/>{{$petName}}
					@endforeach
					</dd>
					<dt class="required"><label for="pet_name">ペットの名前</label></dt>
					<dd><input type="text" id="pet_name" name="pet_name" value="{{old('pet_name')}}" placeholder="例）ポチ、ミケなど" required /></dd>
@if($careType==config('const.CARE_TYPE.REGULAR'))
					<dt class="required"><label for="purpose">来院目的</label></dt>
					<dd>
					@foreach (config('const.PURPOSE') as $purposeKey => $purpose)
						<input type="checkbox" id="purpose_{{$purposeKey}}" name="purpose[]" value="{{$purposeKey}}" {{ is_array(old("purpose")) && in_array("$purposeKey", old("purpose"), true)? 'checked="checked"' : '' }}/>{{$purpose}}
					@endforeach
					</dd>
@endif
					<dt><label for="pet_symptom">症状など</label></dt>
					<dd><textarea id="pet_symptom" name="pet_symptom" placeholder="例）おもちゃを飲み込んだ" rows="5" accesskey="s"></textarea></dd>
				</dl>
				<div style="margin-bottom:10px;">
					<input id="savable" type="checkbox"><label for="savable" style="margin-left:15px;">入力内容をこのブラウザに保存する。次回から入力が簡単になります。</label>
					<p style="margin-left:30px;color:red;">ネットカフェ等の公共のブラウザでの保存は絶対におやめ下さい。</p>
				</div>
				<div class="console">
					<a href="{{route('index')}}" class="btn_cancel" accesskey="c">キャンセル</a>
					<button type="" id="btn_execution" class="btn_execution" accesskey="e">確　認</button>
				</div>
			</form>
		</section>
	</div>
</main>
@include('layouts.footer')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script>
$(function(){
	var isset = function(data){
	    if(data === "" || data === null || data === undefined){
	        return false;
	    }else{
	        return true;
	    }
	};
	function validChar(keyCode) {
			var st = String.fromCharCode(keyCode);
			if ("0123456789".indexOf(st,0) < 0) { 
					return false;
			}
			return true;
	}
	$('input[type=number],input[type=tel]').keypress(function(e){
			return validChar(e.which);
	});
	$('#btn_execution').click(function(ev){
			if ($('#savable').prop('checked')){
					@if($careType==config('const.CARE_TYPE.REGULAR'))
							localStorage.setItem('reserve.patient_no', document.getElementById('patient_no').value);
					@endif
					localStorage.setItem('reserve.name', document.getElementById('name').value);
					localStorage.setItem('reserve.email', document.getElementById('email').value);
					localStorage.setItem('reserve.tel', document.getElementById('tel').value);
					localStorage.setItem('reserve.pet_name', document.getElementById('pet_name').value);
					@foreach (config('const.PET_TYPE_NAME') as $petKey => $petName)
							localStorage.setItem('reserve.pet_type_' + <?php echo $petKey; ?>, document.getElementById("pet_type_" + <?php echo $petKey; ?>).checked);
					@endforeach
					@foreach (config('const.PURPOSE') as $purposeKey => $purposeName)
							localStorage.setItem('reserve.purpose_' + <?php echo $purposeKey; ?>, document.getElementById("purpose_" + <?php echo $purposeKey; ?>).checked);
					@endforeach
			}else{
					localStorage.removeItem('reserve.patient_no');
					localStorage.removeItem('reserve.name');
					localStorage.removeItem('reserve.email');
					localStorage.removeItem('reserve.tel');
					localStorage.removeItem('reserve.pet_name');
					@foreach (config('const.PET_TYPE_NAME') as $petKey => $petName)
							localStorage.removeItem('reserve.pet_type_' + <?php echo $petKey; ?>);
					@endforeach
					@foreach (config('const.PURPOSE') as $purposeKey => $purposeName)
							localStorage.removeItem('reserve.purpose_' + <?php echo $purposeKey; ?>);
					@endforeach
			}
	});
	@if ($careType==config('const.CARE_TYPE.REGULAR'))
	if (isset(localStorage.getItem('reserve.name'))){
			$('#savable').prop('checked',true);
	}
	$('#patient_no').val(localStorage.getItem('reserve.patient_no'));
	$('#name').val(localStorage.getItem('reserve.name'));
	$('#email').val(localStorage.getItem('reserve.email'));
	$('#tel').val(localStorage.getItem('reserve.tel'));
	$('#pet_name').val(localStorage.getItem('reserve.pet_name'));
		@foreach (config('const.PET_TYPE_NAME') as $petKey => $petName)
				var petCheck = localStorage.getItem('reserve.pet_type_' + <?php echo $petKey; ?>);
				if(petCheck != 'false') {
					$('#pet_type_' + <?php echo $petKey; ?>).prop('checked', petCheck);
				}
		@endforeach
		@foreach (config('const.PURPOSE') as $purposeKey => $purposeName)
				var purposeCheck = localStorage.getItem('reserve.purpose_' + <?php echo $purposeKey; ?>);
				if(purposeCheck != 'false') {
					console.log(purposeCheck);
					$('#purpose_' + <?php echo $purposeKey; ?>).prop('checked', purposeCheck);
				}
		@endforeach
	@endif

});
</script>
@endsection
