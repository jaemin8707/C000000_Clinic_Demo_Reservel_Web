@section('header')
<header>
	@if(env('APP_ENV')!=='prod')<div style="color:red;position:absolute;top:0;left:0;">{{env('APP_ENV')}}環境</div>@endif
 <div class="header">
  <h1>{{env('HOSPITAL_NAME','')}}　@yield('heading','')</h1>
 </div>
</header>
@endsection
