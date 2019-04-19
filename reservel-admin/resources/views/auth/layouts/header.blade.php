@section('header')
<header>
 <div class="header">
  <h1>{{env('HOSPITAL_NAME','')}}　@yield('heading','')</h1>
 </div>
</header>
@endsection