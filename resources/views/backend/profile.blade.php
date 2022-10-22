@extends('layouts.backend')

@section('title', __('Edit Profile'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		<!--/Data Entry Form-->
		<div class="row">
			<div class="col-lg-12">
				<form novalidate="" data-validate="parsley" id="DataEntry_formId">
					<div class="card">
						<div class="card-header">{{ __('Edit Profile') }}</div>
						<div class="card-body">
						
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="name"><span class="red">*</span> {{ __('Name') }}</label>
										<input type="text" name="name" id="name" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="email"><span class="red">*</span> {{ __('Email Address') }}</label>
										<input type="email" name="email" id="email" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group relative">
										<label for="password"><span class="red">*</span> {{ __('Password') }}</label>
										<span toggle="#password" class="fa fa-eye field-icon toggle-password"></span>
										<input type="password" name="password" id="password" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="designation"><span class="red">*</span> {{ __('Designation') }}</label>
										<input type="text" name="designation" id="designation" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="phone">{{ __('Phone') }}</label>
										<input type="text" name="phone" id="phone" class="form-control">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="skype_id">{{ __('Skype id') }}</label>
										<input type="text" name="skype_id" id="skype_id" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="facebook_id">{{ __('Facebook id') }}</label>
										<input type="text" name="facebook_id" id="facebook_id" class="form-control">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="address">{{ __('Address') }}</label>
										<textarea name="address" id="address" class="form-control" rows="3"></textarea>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<p class="file-title">{{ __('Upload your profile photo') }}</p>
										<p class="errorMgs"></p>
										<input type="file" name="FileName" id="FileName" class="file-upload">
										<label for="FileName" class="file-uploader" id="file-uploader">
											<img src="{{asset('public/assets/images/default.png')}}">
										</label>
										<input type="text" id="photo" name="photo" class="dnone"/>
										<small class="form-text text-muted">{{ __('The profile image must be a file of type jpg') }}</small>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<input type="text" id="RecordId" name="RecordId" class="dnone">
						</div>
						<div class="card-footer">
							<a id="submit-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!--/Data Entry Form-->
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var userid = "{{ Auth::user()->id }}";
var TEXT = [];
	TEXT['Sorry only you can upload jpg, png and gif file type'] = "{{ __('Sorry only you can upload jpg, png and gif file type') }}";
	TEXT['Sorry file size exceeding from 1 Mb'] = "{{ __('Sorry file size exceeding from 1 Mb') }}";
</script>
<!-- staff js -->
<script src="{{asset('public/pages/profile.js')}}"></script>
@endpush