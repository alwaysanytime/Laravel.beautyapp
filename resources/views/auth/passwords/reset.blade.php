@extends('layouts.app')

@section('title', __('Reset Password'))

@section('content')
@php $gtext = gtext(); @endphp
<!-- main Section -->
<div class="loginsignup-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="login text-center">
					<div class="logo">
						<a href="{{ route('login') }}">
							<img src="{{ $gtext['logo'] ? asset('public/media/'.$gtext['logo']) : asset('public/assets/images/logo.png') }}">
						</a>
					</div>
					
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					
					<form method="POST" action="{{ url('/password_update') }}">
						@csrf
						
						@if($errors->any())
							<ul class="errors-list">
							@foreach($errors->all() as $error)
								<li>{{$error}}</li>
							@endforeach
							</ul>
						@endif
						
						<input type="hidden" name="token" value="{{ $token }}">
						
						<div class="form-group">
							<input type="email" id="email" name="email" class="form-control" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
						</div>
						<div class="form-group">
							<input type="password" id="password" name="password" class="form-control" placeholder="{{ __('Password') }}" required autocomplete="new-password">
						</div>
						<div class="form-group">
							<input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}"  required autocomplete="new-password">
						</div>
						@if($gtext['recaptcha'] == 1)
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
						</div>
						@endif
						<input type="submit" class="btn login-btn" value="{{ __('Reset Password') }}">
					</form>

					@if (Route::has('login'))
					<h3><a href="{{ route('login') }}">{{ __('Back to login') }}</a></h3>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
@if($gtext['recaptcha'] == 1)
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
@endif
@endpush
