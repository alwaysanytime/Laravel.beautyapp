@extends('layouts.backend')

@section('title', __('Client'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<div class="be-heading mb-10">
					<div class="row">
						<div class="col-md-4 mb-10">
							<h2 style="padding-left: 15px">{{ __('Client List') }}</h2>
						</div>
						<div class="col-md-4 mb-10">
							<div class="search">
								<input type="text" id="search_txt" placeholder="{{ __('Search...') }}">
								<span class="search-icon"><i class="fa fa-search"></i></span>
							</div>
						</div>
						<div class="col-md-4 mb-10">
							<a onClick="onFormPanel()" href="javascript:void(0);" class="btn green-btn btn-form fl-right"><i class="fa fa-plus"></i> {{ __('New Client') }}</a>
							<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list fl-right dnone"><i class="fa fa-plus"></i> {{ __('Back to List') }}</a>
						</div>
					</div>
				</div>
				<div id="tw-loader" class="tw-loader">
					<div class="tw-ellipsis">
						<div></div><div></div><div></div><div></div>
					</div>
				</div>
			</div>
		</div>

		<!--/Data grid-->
		<div class="row mt-10 datalist" id="list-panel"></div>
		<!--/Data grid-->

		<!--/Data Entry Form-->
		<div class="row mt-12 dnone" id="form-panel">
			<div class="col-lg-12">
				<form novalidate="" data-validate="parsley" id="DataEntry_formId">
					<div class="card">
						<div class="card-header">{{ __('Client Entry Form') }}</div>
						<div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="salutation">Anrede</label>
                                        <select name="salutation" id="salutation" class="form-control">
                                            <option value="0">Anrede wählen</option>
                                            <option value="2">Frau</option>
                                            <option value="1">Herr</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="firstname"><span class="red">*</span> {{ __('First Name') }}</label>
										<input type="text" name="firstname" id="firstname" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="lastname"><span class="red">*</span> {{ __('Last Name') }}</label>
										<input type="text" name="lastname" id="lastname" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="phone"><span class="red">*</span> {{ __('Mobile') }}</label>
										<input type="text" name="mobile" id="mobile" class="form-control parsley-validated" data-required="true">
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
                                        <label for="email"><span class="red"></span> {{ __('Email Address') }}</label>
                                        <input type="email" name="email" id="email" class="form-control">
                                    </div>
                                </div>
                            </div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="adress1">{{ __('Address') }}</label>
										<input type="text" name="address1" id="address1" class="form-control">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="zipcode">{{ __('Zipcode') }}</label>
										<input type="text" name="zipcode" id="zipcode" class="form-control">
									</div>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<label for="city">{{ __('City') }}</label>
										<input type="text" name="city" id="city" class="form-control">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="notes">{{ __('Notes') }}</label>
										<textarea name="notes" id="notes" class="form-control" rows="4"></textarea>
									</div>
								</div>
							</div>

                            <input type="text" id="RecordId" name="RecordId" class="dnone"/>
						</div>
						<div class="card-footer">
							<a id="submit-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
							<a onClick="onListPanel()" href="javascript:void(0);" class="btn danger-btn btn-list">{{ __('Cancel') }}</a>
						</div>
					</div>
				</form>
			</div>
			<div class="col-lg-4"></div>
		</div>
		<!--/Data Entry Form-->
	</div>
</div>
<!-- /main Section -->

<!-- Profile Info Modal -->
<div class="modal fade" id="View_Id">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Profile') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body pt-0">
				<div id="profile_head" class="profile_head pt-40" data-themecolor="9">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="profile_image" id="profile_image">
									<img src="{{ asset('public/assets/images/default.png') }}">
								</div>
								<div id="profile_name" class="title"></div>
								<p id="profile_desig"></p>
							</div>
						</div>
					</div>
				</div>
				<ul class="profile_info" id="profile_info"></ul>
			</div>
		</div>
	</div>
</div>
<!-- /Profile Info Modal -->
@endsection

@push('scripts')
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var TEXT = [];
	TEXT['Sorry only you can upload jpg, png and gif file type'] = "{{ __('Sorry only you can upload jpg, png and gif file type') }}";
	TEXT['Sorry file size exceeding from 1 Mb'] = "{{ __('Sorry file size exceeding from 1 Mb') }}";
	TEXT['Active'] = "{{ __('Active') }}";
	TEXT['Inactive'] = "{{ __('Inactive') }}";
	TEXT['View'] = "{{ __('View') }}";
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['No data available'] = "{{ __('No data available') }}";
</script>
<!-- client js -->
<script src="{{asset('public/pages/client.js')}}"></script>

@endpush
