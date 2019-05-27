@extends('layouts.common')
@section('title', '休診')
@include('layouts.head')
@section('content')
@section('heading', '休診日')
@include('layouts.header')
<script type="text/javascript">
	window.onload = function() {
		//初期表示は非表示
		document.getElementById("week_label").style.display ="none";
		document.getElementById("week_input").style.display ="none";
		document.getElementById("day_label").style.display ="none";
		document.getElementById("day_input").style.display ="none";
		if ("{{old('create_type')}}" == "{{config('const.CLOSED_CREATE_TYPE.WEEK')}}") {
			document.getElementById("week_label").style.display ="";
			document.getElementById("week_input").style.display ="";
		} else if ("{{old('create_type')}}" == "{{config('const.CLOSED_CREATE_TYPE.DAY')}}") {
			document.getElementById("day_label").style.display ="";
			document.getElementById("day_input").style.display ="";
		}
	}
	function inputValue(value) {

		if(value == "{{config('const.CLOSED_CREATE_TYPE.WEEK')}}") {
			document.getElementById("day_label").style.display ="none";
			document.getElementById("day_input").style.display ="none";
			document.getElementById("week_label").style.display ="";
			document.getElementById("week_input").style.display ="";
		} else if(value == "{{config('const.CLOSED_CREATE_TYPE.DAY')}}") {
			document.getElementById("week_label").style.display ="none";
			document.getElementById("week_input").style.display ="none";
			document.getElementById("day_label").style.display ="";
			document.getElementById("day_input").style.display ="";
		} else {
			document.getElementById("week_label").style.display ="none";
			document.getElementById("week_input").style.display ="none";
			document.getElementById("day_label").style.display ="none";
			document.getElementById("day_input").style.display ="none";
		}
	}
  window.onload = function() {
    //初期表示は非表示
    document.getElementById("week_label").style.display ="none";
    document.getElementById("week_input").style.display ="none";
    document.getElementById("day_label").style.display ="none";
    document.getElementById("day_input").style.display ="none";
    if ("{{old('create_type')}}" == "{{config('const.CLOSED_CREATE_TYPE.WEEK')}}") {
      document.getElementById("week_label").style.display ="";
      document.getElementById("week_input").style.display ="";
    } else if ("{{old('create_type')}}" == "{{config('const.CLOSED_CREATE_TYPE.DAY')}}") {
      document.getElementById("day_label").style.display ="";
      document.getElementById("day_input").style.display ="";
    }
  }
  function inputValue(value) {
    if(value == "{{config('const.CLOSED_CREATE_TYPE.WEEK')}}") {
      document.getElementById("day_label").style.display ="none";
      document.getElementById("day_input").style.display ="none";
      document.getElementById("week_label").style.display ="";
      document.getElementById("week_input").style.display ="";
    } else if(value == "{{config('const.CLOSED_CREATE_TYPE.DAY')}}") {
      document.getElementById("week_label").style.display ="none";
      document.getElementById("week_input").style.display ="none";
      document.getElementById("day_label").style.display ="";
      document.getElementById("day_input").style.display ="";
    }
  }
</script>
<main>
  <div class="wrapper960_closed">
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
          <form method="POST" name="closed_create" action="{{route('closed.create')}}"> 
            @csrf 
            <input type="hidden" name="month" value="{{$month}}">
            <dl class="form_items_closed">
                <dt><span>休日：</span></dt>
                <dd>
                  <select class="status" id="status" name="create_type" onchange="inputValue(this.value)">
                  <option value ="">
                  <option value="{{config('const.CLOSED_CREATE_TYPE.WEEK')}}" {{old('create_type') == config('const.CLOSED_CREATE_TYPE.WEEK') ? 'selected': ''}}>毎週
                  <option value="{{config('const.CLOSED_CREATE_TYPE.DAY')}}"  {{old('create_type') == config('const.CLOSED_CREATE_TYPE.DAY') ? 'selected': ''}}>日
                  </select>
                </dd>
              <dt id="week_label"><span>曜日：</span></dt>
              <dd id="week_input">
                <select name="closed_week">
                <option value="{{config('const.DAY_OF_WEEK_NAME.SUNDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.SUNDAY') ? 'selected' : ''}}>日
                <option value="{{config('const.DAY_OF_WEEK_NAME.MONDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.MONDAY') ? 'selected' : ''}}>月
                <option value="{{config('const.DAY_OF_WEEK_NAME.TUESDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.TUESDAY') ? 'selected' : ''}}>火
                <option value="{{config('const.DAY_OF_WEEK_NAME.WEDNESDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.WEDNESDAY') ? 'selected' : ''}}>水
                <option value="{{config('const.DAY_OF_WEEK_NAME.THURSDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.THURSDAY') ? 'selected' : ''}}>木
                <option value="{{config('const.DAY_OF_WEEK_NAME.FRIDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.FRIDAY') ? 'selected' : ''}}>金
                <option value="{{config('const.DAY_OF_WEEK_NAME.SATURDAY')}}" {{old('closed_week') == config('const.DAY_OF_WEEK_NAME.SATURDAY') ? 'selected' : ''}}>土
                </select>
              </dd>

              <dt id="day_label"><span>日付：</span></dt>
              <dd id="day_input"><input type="text" style="width: 120px;" name="closed_day" placeholder="YYYY-MM-DD" value="{{old('closed_day')}}"></dd>

            <dt><span>区分：</span></dt> 
            <dd>
              <input type="radio" name="closed_type" value="{{config('const.CLOSED_TYPE.MORNING')}}" {{old('closed_type') == config('const.CLOSED_TYPE.MORNING') ? 'checked' : ''}}>午前
              <input type="radio" name="closed_type" value="{{config('const.CLOSED_TYPE.AFTERNOON')}}" {{old('closed_type') == config('const.CLOSED_TYPE.AFTERNOON') ? 'checked' : ''}}>午後
              <input type="radio" name="closed_type" value="{{config('const.CLOSED_TYPE.ALL_DAY')}}" {{old('closed_type') == config('const.CLOSED_TYPE.ALL_DAY') ? 'checked' : ''}}>全日
            </dd>
            <dd>
              <div class="console_closed">
                <button class="btn_closed">追　加</button>
              </div>
            </dd>
          </dl>
          </form>
        </div>
        <div class="tools">
        <div class="counter">
            <span class="time">
            {{date('Y年m月', strtotime($month))}}
            </span>
        </div>
      </div>
        <div class="console_month">
        <form method="GET" action="{{route('closed.index')}}">
            <input type="hidden" name= "month" value={{$prevMonth}} >
          <button class="btn_month"><<前月</button>
        </form>
        <form method="GET" action="{{route('closed.index')}}">
            <input type="hidden" name= "month" value={{$nextMonth}} >
          <button class="btn_month">次月>></button>
        </form>
        </div>
        <!-- 休日一覧表示 -->
        <div class="list_header">
        <span class="a_closed_day">日付</span>
        <span class="a_closed_type">区分</span>
        <span class="a_closed_submit">削除</span>
        </div>
        @foreach($closed as $val)
        <ul class="list">
          <li>
            <div class="a_closed_day">{{date('Y年m月d日', strtotime($val->closed_day))}}({{config('const.DAY_OF_WEEK_DATE')[date('w', strtotime($val->closed_day))]}})</div>
            <div class="a_closed_type">現在：{{config('const.CLOSED_TYPE_NAME')[$val->closed_type]}}<br>
              <form method="POST" action="{{route('closed.update',['closed'=>$val->id])}}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="radio" name="closed_type" value="{{config('const.CLOSED_TYPE.MORNING')}}" @if($val->closed_type==config('const.CLOSED_TYPE.MORNING')) checked @endif>午前</option>
                <input type="radio" name="closed_type" value="{{config('const.CLOSED_TYPE.AFTERNOON')}}" @if($val->closed_type==config('const.CLOSED_TYPE.AFTERNOON')) checked @endif>午後</option>
                <input type="radio" name="closed_type" value="{{config('const.CLOSED_TYPE.ALL_DAY')}}" @if($val->closed_type==config('const.CLOSED_TYPE.ALL_DAY')) checked @endif>全日</option>
                <input class="submit_button" type="submit" name="update" value="更新">
              </form>
            </div>
            <div class="a_closed_submit">
              <form method="POST" action="{{route('closed.delete',['closed'=>$val->id])}}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="closed_day" value="{{$val->closed_day}}">
                <input class="submit_button" type="submit" name="delete" value="削除">
              </form>
            </div>
          </li>
      </ul>
        @endforeach
        <div class="console_closed">
            <a href="{{route('reserve.index')}}">
            <button class="btn_closed" type="submit" value="true">受付一覧</button>
            </a>
        </div>  
        </table>
       </body>
     </section>
   </div>
</main>
@include('layouts.footer')
@endsection