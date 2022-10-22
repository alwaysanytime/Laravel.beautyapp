@extends('layouts.app')

@section('title', __('Register'))

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
					<div id="msg" class="dnone"></div>
					<div class="btn-group btn-group-sm group-btn">
						<button id="staffid" onclick="onStaff()" type="button" class="btn green-btn active">Staff</button>
						<button id="clientid" onclick="onClient()" type="button" class="btn green-btn">Client</button>
					</div>
					<form id="DataEntry_formId">
						<div class="form-group">
							<input id="name" name="name" type="text" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autocomplete="name" autofocus>
						</div>
						
						<div class="form-group">
							<input id="email" name="email" type="email" class="form-control" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email">
						</div>
						
						<div class="form-group">
							<input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}" required autocomplete="new-password">
						</div>

						<div id="designationid" class="form-group">
							<input type="text" id="designation" name="designation" class="form-control" placeholder="{{ __('Designation') }}" required autocomplete="designation">
						</div>
						<div id="countryid" class="form-group text-left dnone">
							<select name="country_id" id="country_id" class="chosen-select form-control" tabindex="1"></select>
						</div>
						@if($gtext['recaptcha'] == 1)
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
						</div>
						@endif
						<input type="text" id="StaffClient" name="StaffClient" class="dnone">
						<input type="submit" class="btn login-btn" value="{{ __('Register') }}">
					</form>
					
					@if (Route::has('password.request'))
					<h3><a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></h3>
					@endif
					
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
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var isReCaptcha = "{{ $gtext['recaptcha'] }}";
</script>
<script src="{{asset('public/pages/signup.js')}}"></script>
@endpush
