@extends('layouts.common')
@section('title', 'お知らせ一覧- ')
@section('refresh')
<meta http-equiv="refresh" content="120">
@endsection
@include('layouts.head')
@section('content')
@section('heading', 'お知らせ')
@include('layouts.header')
<main>
  <div class="wrapper960_notice">
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
    <body>
        <form method="POST" action="{{route('notice.store_new_notice')}}"> 
            @csrf
            <dl class="form_items_notice">
              <dt>
                <span>お知らせ：</span>
              </dt>
              <dd>
                <input type="text" name="notice_text" value="{{old('notice_text')}}">
              </dd>
              <dd>
                <div class="console_notice">
                  <button class="btn_notice">追　加</button>
                </div>
              </dd>
            </dl>
          </form>
    </body>
  </div>
  <div class="wrapper">
      @if (session('flash_message'))
      <div class="flash_message">
          {{ session('flash_message') }}
      </div>
  @endif
    <section>
      <body>
        <div class="list_header">
          <span class="">表示</span>
          <span class="a_notice_text">お知らせ内容</span>
          <span class="a_button_area">削除</span>
        </div>
        <form action="{{route('notice.store')}}" method="POST">
            {{csrf_field()}}
        <?php $i = 1; ?>
        <ul class="list" id="list">
          @if (isset($notices))
            @foreach ($notices as $notice)
            <li data-id="{{$notice->id}}">
                <input type="hidden" name="post_flg[{{$notice->id}}]" value="0">
              <div class=""><input type="checkbox" name="post_flg[{{$notice->id}}]" @if($notice->post_flg) checked @endif value="1"> </div>
              <div class="a_notice_text">{{$notice->notice_text}}</div>
            <div class="a_button_area"><a href="{{route('notice.delete',['id' => $notice->id])}}">削除</a>
            </li>
            <?php $i++ ;?>
            @endforeach
            <input type="hidden" name="sort" id="sort">
          @endif
        </ul>
        <div class="console_notice">
          <button  class="btn_notice" type="submit" name="submit">順番&表示変更</button>
        </div>
      </form>
      </div>
    </section>
  </div>
</main>
    @include('layouts.footer')
    <script>
    $(function() {
      Sortable.create(list, {
        group: "save",
        store: {
          get: function (sortable) {
            var order = localStorage.getItem(sortable.options.group.name);
            return order ? order.split(', ') : [];
          },
          set: function (sortable) {
            var order = sortable.toArray();
            $('#sort').val(order);
          }
        }
      });
      });
    </script>
    @endsection