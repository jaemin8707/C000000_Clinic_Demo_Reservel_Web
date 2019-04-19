@extends('auth.layouts.common')
@section('title', 'パスワードリセット - ')
@include('auth.layouts.head')
@section('content')
@section('heading', '管理画面　パスワードリセット')
@include('auth.layouts.header')
<main>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8" style="margin-top:100px;padding:50px;">
				<div class="card">
					<div class="card-header">{{ __('パスワードのリセット') }}</div>
					<div class="card-body">
						<form method="POST" action="{{ route('password.update') }}">
							@csrf
							<input type="hidden" name="token" value="{{ $token }}">
							<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('メールアドレス') }}</label>
								<div class="col-md-6">
									<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
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
								<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('パスワード(確認用)') }}</label>
								<div class="col-md-6">
									<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
								</div>
							</div>
							<div class="form-group row mb-0">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary">{{ __('パスワードをリセットする') }}</button>
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
