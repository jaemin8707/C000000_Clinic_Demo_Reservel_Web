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
						@if (session('status'))
						<div class="alert alert-success" role="alert">
								{{ session('status') }}
						</div>
						@endif
						<form method="POST" action="{{ route('password.email') }}">
							@csrf
							<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('メールアドレス') }}</label>
								<div class="col-md-6">
										<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
										@if ($errors->has('email'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
								</div>
							</div>
							<div class="form-group row mb-0">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary">{{ __('パスワードリセット用のメールを送信する') }}</button>
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
