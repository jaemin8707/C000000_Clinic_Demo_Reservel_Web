@extends('layouts.common')
@section('title', '予約受付 - ')
@include('layouts.head')
@section('content')
@section('heading', '予約受付')
@include('layouts.header')
<?php $careTypeName = config('const.CARE_TYPE_NAME')[$request->careType]; ?>
<main>
  <div class="wrapper">
    <section>
      <div class="clinicType {{$careTypeName['class_name']}}">{{$careTypeName['name']}}予約申し込み</div>
    </section>
    <section>
      <form action="{{route('reserve.store')}}" method="POST">
        {{csrf_field()}}
        <input type="hidden" name="careType"        value="{{$request->careType}}" />
        <input type="hidden" name="patient_no" value="{{$request->patient_no}}" />
        <input type="hidden" name="name"            value="{{$request->name}}" />
        <input type="hidden" name="email"           value="{{$request->email}}" />
        <input type="hidden" name="tel"             value="{{$request->tel}}" />
        <input type="hidden" name="pet_type"        value="{{$request->pet_type}}" />
        <input type="hidden" name="pet_name"        value="{{$request->pet_name}}" />
        <input type="hidden" name="pet_symptom"     value="{{$request->pet_symptom}}" />
        <div class="type"><span>受付区分</span>：<span>{{$careTypeName['name']}}</span></div>
        <dl class="form_items">
@if($request->careType==2)
          <dt><span>診察券番号</span></dt>
          <dd><span>{{$request->patient_no}}</span></dd>
@endif
          <dt class="required"><span>お名前</span></dt>
          <dd><span>{{$request->name}}</span></dd>
          <dt class="required"><span>メールアドレス</span></dt>
          <dd><span>{{$request->email}}</span></dd>
          <dt class="required"><label for="tel">電話番号</label></dt>
          <dd><span>{{$request->tel}}</span></dd>
          <dt class="required"><span>ペットの種類</span></dt>
          <dd><span>{{$request->pet_type}}</span></dd>
          <dt class="required"><span>ペットの名前</span></dt>
          <dd><span>{{$request->pet_name}}</span></dd>
          <dt><span>症状など</span></dt>
          <dd><span class="symptom">{!! nl2br(htmlspecialchars($request->pet_symptom)) !!}</span></dd>
        </dl>
        <div class="console">
          <a href="#" class="btn_cancel" onclick="javascript:window.history.back(-1);return false;">戻　る</a>
          <button type="submit" class="btn_execution">予　約</button>
        </div>
      </form>
    </section>
  </div>
</main>
@include('layouts.footer')
@endsection
