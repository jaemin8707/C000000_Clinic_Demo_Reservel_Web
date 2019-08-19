@extends('layouts.common')
@section('title', 'Web受付 - ')
@include('layouts.head')
@section('content')
@section('heading', 'Web受付')
@include('layouts.header')
<?php $careTypeName = config('const.CARE_TYPE_NAME')[$careType]; ?>
<main>
  <div class="wrapper">
    <section>
      <div class="clinicType {{$careTypeName['class_name']}}">{{$careTypeName['name']}}受付申し込み</div>
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
          <dt class="required"><label for="patient_no">診察券番号<p style="font-size:13px; color:red;">※診察券番号がわからない方は「00000」と入力してください</p></label></dt>
          <dd><input type="text" id="patient_no" name="patient_no" maxlength="5" placeholder="例）10001 " required />
          </dd>
          @endif
          <dt class="required"><label for="name">飼い主氏名</label></dt>
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
        <div style="margin-bottom:10px; text-align:center;">
          <input id="savable" type="checkbox"><label for="savable" style="margin-left:15px; margin-bottom:0;font-weight:bold;">入力内容をこのブラウザに保存する。</label>
          <p style="margin-bottom:0.5rem;font-size:0.9rem;">次回から入力が簡単になります。</p>
          <p style="margin-left:30px;margin-bottom:1.5rem;color:red;font-size:0.9rem;">ネットカフェ等の公共のブラウザでの保存は絶対におやめ下さい。</p>
        </div>
        <div class="privacy_attention">
          <h2>個人情報の取扱いについて</h2>
          <p class="privacy_attention_lead">お客様の個人情報をお預かりするにあたり、個人情報の取り扱いに最大限の注意を払っております。<br>
           来院時、または当ウェブサイトをご利用の方からご提供いただきました個人情報につきましては、管理体制を整え、その保護・管理に努めます。</p>
          <p class="privacy_detail"><span class="privacy_detail_btn">「個人情報の取り扱いについて」詳細はこちら</span></p>
         </div>
          <p class="privacy_attention_prompt">上記事項をご確認の上、ご同意いただける方は下の「同意して次へ」をクリックしてください。</p>
             <div class="console">
               <a href="{{route('index')}}" class="btn_cancel" accesskey="c">キャンセル</a>
               <button type="submit" id="btn_execution" class="btn_execution" accesskey="e">同意して次へ</button>
             </div>
      </form>
    </section>
  </div>
  <!-- 個人情報の取り扱いについて -->
  <div class="modalWin">
    <div class="modalContents">
     <div class="privacy_policy wrapper">
      <section class="privacy_section">
       <h2>個人情報の利用目的</h2>
       <p>ご来院された方に対し、安心できる医療サービスを継続して提供することを目的としています。</p>
       <ul>
        <li>・より良い医療サービスの提供をおこなうため。</li>
        <li>・当院からのご連絡やお知らせなどの情報提供</li>
       </ul>
      </section>
      <section class="privacy_section">
       <h2>個人情報の管理</h2>
       <p>お客様の個人情報の漏洩、流失および紛失などの危険発生の防止に努めます。</p>
      </section>
      <section class="privacy_section">
       <h2>個人情報の第三者への開示・提供の禁止</h2>
       <p>当院は、お客さまよりお預かりした個人情報を適切に管理し、次のいずれかに該当する場合を除き、個人情報を第三者に開示いたしません。</p>
       <ul>
        <li>お客さまの同意がある場合</li>
        <li>お客さまが希望されるサービスを行なうために当院が業務を委託する業者に対して開示する場合</li>
        <li>法令に基づき開示することが必要である場合</li>
       </ul>
      </section>
      <section class="privacy_section">
       <h2>個人情報の安全管理</h2>
       <p>当院は、個人情報の漏えい・紛失防止のため、また正確性及び安全性確保のために、セキュリティに万全の対策を講じています。</p>
      </section>
      <section class="privacy_section">
       <h2>クッキー(Cookie)の利用について</h2>
       <p>当受付システムをお客さまがより便利に利用して頂くにあたりクッキー(Cookie)<sup>※</sup>を使用しています。<br>
        <span style="font-weight:bold;color:#666;">※Cookieとは、お客様の入力内容を端末に記録する仕組みのことです。</span></p>
      </section>
      <section class="privacy_section">
       <h2>ご本人の照会</h2>
       <p>お客さまがご本人の個人情報の照会・修正・削除などをご希望される場合には、ご本人であることを確認の上、対応させていただきます。</p>
      </section>
      <section class="privacy_section">
       <h2>法令、規範の遵守と見直し</h2>
       <p>当院は、保有する個人情報に関して適用される日本の法令を遵守するとともに、本ポリシーの内容を適宜見直し、その改善に努めます。</p>
      </section>
      <section class="privacy_section">
       <h2>お問い合せ</h2>
       <p>当院の個人情報の取扱に関するお問い合せは下記までご連絡ください。</p>
      </section>
      <div class="privacy_address">
     <h3>聖母坂どうぶつ病院</h3>
     <p>〒161-0033<br>
     東京都新宿区下落合４丁目６−１０ 守屋ビル101<br>
     TEL:03-5906-5866</p>
      </div>
      <div class="close_btn_box">
       <span class="closeBtn">閉じる</span>
      </div>
     </div>
   </div>
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

$(function(){
 
  //-- モーダル表示 --
  function viewWin(target){
    $('body').css('overflow','hidden');
  $(target).show();
  $('.modalWin').fadeIn(200);
  $('.modalWin').css('display','block');
  }
 

  //-- モーダル閉じる --
  function close(){
    $('body').css('overflow','auto');
    $('.modalWin').fadeOut(200);
  $('.profile').hide();
  }
 

  $(function(){

  //-- ボタンクリック時の処理実行 --
    $('.privacy_detail_btn').on('click',function(){
      var target = $(this).attr('data-target');
      viewWin(target);
    });



  //-- 閉じるボタンクリック時の処理実行 --
    $('.closeBtn').on('click',function(){
      close();
    });

  });
 
 });
</script>
@endsection
