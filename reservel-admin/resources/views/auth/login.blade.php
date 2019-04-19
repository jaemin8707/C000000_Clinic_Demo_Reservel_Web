@extends('auth.layouts.common')
@section('title', 'ログイン - ')
@include('auth.layouts.head')
@section('content')
@section('heading', '管理画面　ログイン')
@include('auth.layouts.header')
<main>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card" style="margin-top:100px;padding:50px;">
					<div class="card-body">
						<form method="POST" action="{{ route('login') }}">
							@csrf
							<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('メールアドレス') }}</label>
								<div class="col-md-6">
										<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
										@if ($errors->has('email'))
												<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('email') }}</strong>
												</span>
										@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('パスワード') }}</label>

								<div class="col-md-6">
										<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

										@if ($errors->has('password'))
												<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('password') }}</strong>
												</span>
										@endif
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6 offset-md-4">
										<div class="form-check">
												<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

												<label class="form-check-label" for="remember">
														{{ __('次回から入力省略') }}
												</label>
										</div>
								</div>
							</div>
							<div class="form-group row mb-0">
								<div class="col-md-8 offset-md-4">
										<button type="submit" class="btn btn-primary" style="background-color:#0b7234;">
												{{ __('ログイン') }}
										</button>

										@if (Route::has('password.request'))
												<a class="btn btn-link" href="{{ route('password.request') }}" style="color:#0b7234;">
														{{ __('パスワードを忘れた方') }}
												</a>
										@endif
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@include('auth.layouts.footer')
@endsection
