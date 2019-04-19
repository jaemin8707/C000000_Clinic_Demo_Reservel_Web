@section('header')
<header>
  @if(env('APP_ENV')!=='prod')<div style="color:red;position:absolute;top:0;left:0;">{{env('APP_ENV')}}環境</div>@endif
  <div class="header">
    <h1>{{env('HOSPITAL_NAME','')}}　@yield('heading','')</h1>
    <div class="console_logout">
      <form id="logout-form" action="{{ route('logout') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit" class="btn_logout">ログアウト</button>
      </form>
    </div>
  </div>
</header>
@endsection
