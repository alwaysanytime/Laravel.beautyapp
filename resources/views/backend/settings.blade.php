@extends('layouts.backend')

@section('title', __('Settings'))

@section('content')
@php 
$gtext = gtext(); 
$getStripeInfo = getStripeInfo();
@endphp
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
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
					<div class="card-header">{{ __('Settings') }}</div>
					<div class="card-body tabs-area p-0">
						<ul class="tabs-nav">
							<li><a class="active" id="tabId-1" onClick="onClickSetting(1)" href="javascript:void(0);"><i class="fa fa-wrench"></i>{{ __('Global Setting') }}</a></li>
							<li><a id="tabId-2" onClick="onClickSetting(2)" href="javascript:void(0);"><i class="fa fa-cog"></i>{{ __('Google reCAPTCHA') }}</a></li>
							<li><a id="tabId-3" onClick="onClickSetting(3)" href="javascript:void(0);"><i class="fa fa-cog"></i>{{ __('Mail Setting') }}</a></li>
							<li><a id="tabId-4" onClick="onClickSetting(4)" href="javascript:void(0);"><i class="fa fa-cog"></i>{{ __('Stripe Settings') }}</a></li>
							<li><a id="tabId-5" onClick="onClickSetting(5)" href="javascript:void(0);"><i class="fa fa-cog"></i>{{ __('Theme Register') }}</a></li>
						</ul>
						<div class="tabs-body">
							<!--Global Setting-->
							<div id="GlobalSetting">
								<form novalidate="" data-validate="parsley" id="DataEntry_formId">
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label for="company_name"><span class="red">*</span> {{ __('Company Name') }}</label>
												<input type="text" name="company_name" id="company_name" class="form-control parsley-validated" data-required="true">
											</div>
											<div class="form-group">
												<label for="company_title"><span class="red">*</span> {{ __('Company Title') }}</label>
												<input type="text" name="company_title" id="company_title" class="form-control parsley-validated" data-required="true">
											</div>
											<div class="form-group">
												<label for="siteurl"><span class="red">*</span> {{ __('Site URL') }}</label>
												<input type="text" name="siteurl" id="siteurl" class="form-control parsley-validated" data-required="true">
												<small class="form-text text-muted">e.g. <strong>http://companyname.com</strong></small>
											</div>
											<div class="form-group">
												<label for="timezone_id"><span class="red">*</span> {{ __('Time Zone') }}</label>
												<select name="timezone_id" id="timezone_id" class="chosen-select form-control parsley-validated" data-required="true" tabindex="1">
												</select>
											</div>
											<div class="form-group">
												<label><span class="red">*</span> {{ __('Theme color') }}</label>
												<div id="color-picker" class="input-group tw-picker">
													<input name="theme_color" id="theme_color" type="text" value="#38a677" class="form-control"/>
													<span class="input-group-addon"><i></i></span>
												</div>
											</div>
											<div class="form-group">
												<label for="favicon"><span class="red">*</span> {{ __('Favicon') }}</label>
												<p class="errorMgs" id="favicon_errorMgs"></p>
												<div class="file_up">
													<input type="text" name="favicon" id="favicon" class="form-control parsley-validated" data-required="true" readonly>
													<div class="file_browse_box">
														<input type="file" name="load_favicon" id="load_favicon" class="file_browse">
														<label for="load_favicon" class="file_browse_icon"><i class="fa fa-window-restore"></i>Browse</label>
													</div>
												</div>
												<small class="form-text text-muted">favicon.ico 32x32 pixels. <a target="_blank" href="https://www.favicon-generator.org/">Favicon Generator</a></small>
												<div class="file_up_box favicon-w" id="favicon_show"></div>
											</div>
											
											<div class="form-group">
												<label for="logo"><span class="red">*</span> {{ __('Logo') }}</label>
												<p class="errorMgs" id="logo_errorMgs"></p>
												<div class="file_up">
													<input type="text" name="logo" id="logo" class="form-control parsley-validated" data-required="true" readonly>
													<div class="file_browse_box">
														<input type="file" name="load_logo" id="load_logo" class="file_browse">
														<label for="load_logo" class="file_browse_icon"><i class="fa fa-window-restore"></i>Browse</label>
													</div>
												</div>
												<small class="form-text text-muted">{{ __('The logo must be a file of type png') }}</small>
												<div class="file_up_box logo-w" id="logo_show"></div>
											</div>
											<input type="text" id="RecordId" name="RecordId" class="dnone"/>
										</div>
										<div class="col-lg-4"></div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="global-setting-form" href="javascript:void(0);" class="btn green-btn">{{ __('Save') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Global Setting-->

							<!--Google reCAPTCHA Setting-->
							<div id="GoogleRecaptchaSetting" class="dnone">
								<form novalidate="" data-validate="parsley" id="GoogleRecaptcha_formId">
									<div class="row">
										<div class="col-lg-8">
											<div class="tw_checkbox checkbox_group">
												<input id="recaptcha" name="recaptcha" type="checkbox" <?php echo $gtext['recaptcha'] == 1 ? 'checked' : ''; ?> >
												<label for="recaptcha">{{ __('Enable/Disable') }}</label>
												<span></span>
											</div>
											<div class="form-group">
												<label for="sitekey"><span class="red">*</span> {{ __('Site Key') }}</label>
												<input type="text" name="sitekey" id="sitekey" class="form-control parsley-validated" data-required="true" value="<?php echo $gtext['sitekey']; ?>">
											</div>
											<div class="form-group">
												<label for="secretkey"><span class="red">*</span> {{ __('Secret Key') }}</label>
												<input type="text" name="secretkey" id="secretkey" class="form-control parsley-validated" data-required="true" value="<?php echo $gtext['secretkey']; ?>">
												<small class="form-text text-muted"><a target="_blank" href="https://www.google.com/recaptcha/admin/create">Create Google reCAPTCHA v2</a></small>
											</div>
											<input type="text" name="setting_id" class="dnone" value="<?php echo $gtext['setting_id']; ?>">
										</div>
										<div class="col-lg-4"></div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="recaptcha-submit-form" href="javascript:void(0);" class="btn green-btn">{{ __('Save') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Google reCAPTCHA Setting-->
							
							<!--Mail Setting-->
							<div id="MailSetting" class="dnone">
								<form novalidate="" data-validate="parsley" id="MailSetting_formId">
									<div class="row">
										<div class="col-lg-12">
											<div class="tw_checkbox checkbox_group">
												<input id="isnotification" name="isnotification" type="checkbox" <?php echo $gtext['isnotification'] == 1 ? 'checked' : ''; ?>>
												<label for="isnotification">{{ __('Enable/Disable') }}</label>
												<span></span>
											</div>
											<div class="form-group">
												<label for="email"><span class="red">*</span> {{ __('System Mail Address') }}</label>
												<input type="email" name="email" id="email" value="<?php echo $gtext['fromMailAddress']; ?>" class="form-control parsley-validated" data-required="true">
												<small class="form-text text-muted">{{ __('The mail address must be a admin e-mail address') }} e.g. <strong>admin@companyname.com</strong></small>
											</div>
											<div class="form-group">
												<label for="tomailaddress"><span class="red">*</span> {{ __('Administrator Recipient Mail Address') }}</label>
												<input type="email" name="tomailaddress" id="tomailaddress" value="<?php echo $gtext['toMailAddress']; ?>" class="form-control parsley-validated" data-required="true">
											</div>
											<h4>{{ __('Mail Templates') }}</h4>
											<div class="accordion-section settings-accordion mt-15">
												<div id="accordion" class="icon angle-icon">
												  <div id="MailSetting_id"></div>
												</div>
											</div>													

											<input type="text" name="setting_id" class="dnone" value="<?php echo $gtext['setting_id']; ?>">
										</div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="mailsetting-submit-form" href="javascript:void(0);" class="btn green-btn">{{ __('Save') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Mail Setting-->
							
							<!--Stripe Settings-->
							<div id="StripeSettings" class="dnone">
								<form novalidate="" data-validate="parsley" id="StripeSettings_formId">
									<div class="row">
										<div class="col-lg-8">
											<div class="tw_checkbox checkbox_group">
												<input id="isenable" name="isenable" type="checkbox" <?php echo $getStripeInfo['isenable'] == 1 ? 'checked' : ''; ?> >
												<label for="isenable">{{ __('Enable/Disable') }}</label>
												<span></span>
											</div>
											<div class="form-group">
												<label for="stripe_key">{{ __('Stripe Key') }}</label>
												<input type="text" name="stripe_key" id="stripe_key" class="form-control" value="<?php echo $getStripeInfo['stripe_key']; ?>">
											</div>
											<div class="form-group">
												<label for="stripe_secret">{{ __('Stripe Secret') }}</label>
												<input type="text" name="stripe_secret" id="stripe_secret" class="form-control" value="<?php echo $getStripeInfo['stripe_secret']; ?>">
												<small class="form-text text-muted"><a target="_blank" href="https://stripe.com/">Create an Account Stripe</a></small>
											</div>
											<input type="text" name="stripe_id" id="stripe_id" class="dnone" value="<?php echo $getStripeInfo['stripe_id']; ?>">
										</div>
										<div class="col-lg-4"></div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="stripe-submit-form" href="javascript:void(0);" class="btn green-btn">{{ __('Save') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Stripe Settings-->
							
							<!--Theme Register-->
							<div id="PurchaseCodeId" class="dnone">
								<div class="mt-40 dnone" id="deregister_id">
									<div class="row">
										<div class="col-lg-8">
											<strong>{{ __('Theme is registered') }}</strong>
											<p>*********-****-****-****-************ <span class="tik"><i class="fa fa-check"></i></span></p>
										</div>
										<div class="col-lg-4"></div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a onClick="onPcodeDelete()" href="javascript:void(0);" class="btn danger-btn">{{ __('Deregister Theme') }}</a>
										</div>
									</div>
								</div>
								<div class="dnone" id="registered_id">
									<form novalidate="" data-validate="parsley" id="PurchaseCode_formId">
										<div class="row">
											<div class="col-lg-8">
												<div class="form-group">
													<label for="pcode"><span class="red">*</span> {{ __('Purchase Code') }}</label>
													<input type="text" name="pcode" id="pcode" class="form-control parsley-validated" data-required="true">
													<small class="form-text text-muted">Please provide valid purchase code.</small>
												</div>
												<input type="text" name="pcode_id" id="pcode_id" class="dnone">
											</div>
											<div class="col-lg-4"></div>
										</div>
										<div class="row tabs-footer mt-15">
											<div class="col-lg-12">
												<a id="pcode-submit-form" href="javascript:void(0);" class="btn green-btn">{{ __('Register Theme') }}</a>
											</div>
										</div>
									</form>
								</div>
								<div class="row mt-15">
									<div class="col-lg-12">
										<p><strong>Note:</strong> One standard license is valid only for 1 website. Running multiple websites on a single license is a copyright violation.</p>
									</div>
								</div>
							</div>
							<!--/Theme Register-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
<!-- bootstrap-colorpicker js -->
<link href="{{asset('public/assets/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
<script src="{{asset('public/assets/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var userid = "{{ Auth::user()->id }}";
var TEXT = [];
	TEXT['Sorry only you can upload jpg, png and gif file type'] = "{{ __('Sorry only you can upload jpg, png and gif file type') }}";
	TEXT['Sorry file size exceeding from 1 Mb'] = "{{ __('Sorry file size exceeding from 1 Mb') }}";
	TEXT['Subject'] = "{{ __('Subject') }}";
	TEXT['Body'] = "{{ __('Body') }}";
	TEXT['Do you really want to deregister the theme'] = "{{ __('Do you really want to deregister the theme') }}";
</script>
<!-- settings js -->
<script src="{{asset('public/pages/settings.js')}}"></script>

@endpush