@extends('layouts.backend')

@section('title', __('Zoom Settings'))

@section('content')
@php $szoom = szoom(); @endphp
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else	
		<div class="row mb-10">
			<div class="col-lg-12">
				<div id="tw-loader" class="tw-loader">
					<div class="tw-ellipsis">
						<div></div><div></div><div></div><div></div>
					</div>						
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">{{ __('Zoom Meeting') }}</div>
					<div class="card-body tabs-area p-0">
						<ul class="tabs-nav">
							<li><a class="active" id="tabId-1" href="{{ route('backend.upcoming-meeting') }}"><i class="fa fa-exchange"></i>{{ __('Upcoming Meeting') }}</a></li>
							<li><a id="tabId-2" href="{{ route('backend.previous-meeting') }}"><i class="fa fa-exchange"></i>{{ __('Previous Meeting') }}</a></li>
							<li><a id="tabId-3" href="{{ route('backend.live-meeting') }}"><i class="fa fa-exchange"></i>{{ __('Live Meeting') }}</a></li>
							<li><a id="tabId-4" href="{{ route('backend.zoom-settings') }}"><i class="fa fa-cog"></i>{{ __('Zoom Settings') }}</a></li>
						</ul>
						<div class="tabs-body">
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-lg-8">
										<div class="form-group">
											<label for="zoom_api_key"><span class="red">*</span> {{ __('Zoom API Key') }}</label>
											<input type="text" name="zoom_api_key" id="zoom_api_key" class="form-control parsley-validated" data-required="true" value="<?php echo $szoom['zoom_api_key']; ?>">
										</div>
										<div class="form-group">
											<label for="zoom_api_secret"><span class="red">*</span> {{ __('Zoom API Secret') }}</label>
											<input type="text" name="zoom_api_secret" id="zoom_api_secret" class="form-control parsley-validated" data-required="true" value="<?php echo $szoom['zoom_api_secret']; ?>">
											<small class="form-text text-muted"><a target="_blank" href="https://www.teamwork-laravel.themeposh.xyz/documentation/#zoommeeting">Zoom Meeting Documentation</a></small>
										</div>
										<input type="text" id="zoomSettingId" name="id" class="dnone" value="<?php echo $szoom['id']; ?>">
									</div>
									<div class="col-lg-4"></div>
								</div>
								<div class="row tabs-footer mt-15">
									<div class="col-lg-12">
										<a id="submit-form" href="javascript:void(0);" class="btn green-btn">{{ __('Save') }}</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')

<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var userid = "{{ Auth::user()->id }}";
</script>
<!-- js -->
<script src="{{asset('public/pages/zoom_settings.js')}}"></script>
@endpush