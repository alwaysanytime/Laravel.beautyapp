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
					
					<p>{{ __('Enter your email address below and we will send you a link to reset your password') }}</p>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					<form method="POST" action="{{ url('/reset_password') }}">
						@csrf
						
						@if($errors->any())
							<ul class="errors-list">
							@foreach($errors->all() as $error)
								<li>{{$error}}</li>
							@endforeach
							</ul>
						@endif
						<div class="form-group">
							<input type="email" id="email" name="email" class="form-control" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
						</div>
						@if($gtext['recaptcha'] == 1)
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
						</div>
						@endif
						<input type="submit" class="btn login-btn" value="Send Request">
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
