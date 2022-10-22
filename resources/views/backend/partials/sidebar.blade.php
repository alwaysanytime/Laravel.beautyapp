
<div id="side_close_on_contract" class="left-sidebar">
	<div class="logo">
		<a href="{{ url('/') }}"><img src="{{ $gtext['logo'] ? asset('public/media/'.$gtext['logo']) : asset('public/assets/images/logo.png') }}"></a>
	</div>
	<ul class="left-main-menu">
		@if(Auth::user()->role ==1)
		<li><a href="{{ route('backend.dashboard') }}"><i class="fa fa-tachometer"></i><span>{{ __('Dashboard') }}</span></a></li>
		<li><a href="{{ route('backend.calendar') }}"><i class="fa fa-calendar"></i><span>{{ __('Kalender') }}</span></a></li>
		<li><a href="{{ route('backend.client') }}"><i class="fa fa-users"></i><span>{{ __('Kunden') }}</span></a></li>
		@else
		<li><a href="{{ route('backend.dashboard') }}"><i class="fa fa-tachometer"></i><span>{{ __('Dashboard') }}</span></a></li>
		<li><a href="{{ route('backend.calendar') }}"><i class="fa fa-calendar"></i><span>{{ __('Kalender') }}</span></a></li>
		<li><a href="{{ route('backend.client') }}"><i class="fa fa-users"></i><span>{{ __('Kunden') }}</span></a></li>
		@endif
	</ul>
</div>
