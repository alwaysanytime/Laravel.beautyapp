@extends('layouts.backend')

@section('title', __('Project'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<div class="be-heading mb-10">
					<div class="row">
						<div class="col-md-4 mb-10">
							<h2>{{ __('Project List') }}</h2>
						</div>
						<div class="col-md-4 mb-10">
							<div class="search">
								<input type="text" id="search_txt" placeholder="{{ __('Search...') }}">
								<span class="search-icon"><i class="fa fa-search"></i></span>
							</div>
						</div>
						<div class="col-md-4 mb-10">
							<a onClick="onFormPanel()" href="javascript:void(0);" class="btn green-btn btn-form fl-right"><i class="fa fa-plus"></i> {{ __('New Project') }}</a>
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
		<div class="row datalist mt-10" id="list-panel"></div>
		<!--/Data grid-->

		<!--/Data Entry Form-->
		<div class="row mt-10 dnone" id="form-panel">
			<div class="col-lg-8">
				<form novalidate="" data-validate="parsley" id="DataEntry_formId">
					<div class="card">
						<div class="card-header">{{ __('Project Entry Form') }}</div>
						<div class="card-body">

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="project_name"><span class="red">*</span> {{ __('Project Name') }}</label>
										<input type="text" name="project_name" id="project_name" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="start_date"><span class="red">*</span> {{ __('Start Date') }}</label>
										<input type="text" name="start_date" id="start_date" class="form-control parsley-validated" data-required="true" placeholder="yyyy-mm-dd">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="end_date"><span class="red">*</span> {{ __('End Date') }}</label>
										<input type="text" name="end_date" id="end_date" class="form-control parsley-validated" data-required="true" placeholder="yyyy-mm-dd">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="budget"><span class="red">*</span> {{ __('Budget') }}({{ __('Currency') }})</label>
										<input type="number" name="budget" id="budget" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="client_id"><span class="red">*</span> {{ __('Client') }}</label>
										<select name="client_id" id="client_id" class="chosen-select form-control parsley-validated" data-required="true" tabindex="1">
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status_id"><span class="red">*</span> {{ __('Status') }}</label>
										<select name="status_id" id="status_id" class="chosen-select form-control parsley-validated" data-required="true" tabindex="2">
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="description">{{ __('Description') }}</label>
										<textarea name="description" id="description" class="form-control" rows="3"></textarea>
									</div>
								</div>
							</div>

							<input type="text" name="userid" id="userid" class="dnone" value="{{ Auth::user()->id }}">
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

<!-- Invite Modal -->
<div class="modal fade" id="Invite_Id">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="Invite_formId">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">{{ __('Invite to project') }}</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<div class="form-group">
						<label for="staff_id"><span class="red">*</span> {{ __('Staff/Client') }}</label>
						<select name="staff_id" id="staff_id" class="chosen-select form-control" tabindex="-1">
						</select>
					</div>
					<input type="text" name="userid" class="dnone" value="{{ Auth::user()->id }}">
					<input type="text" id="project_id" name="project_id" class="dnone"/>
					<center>
						<a href="javascript:void(0);" onclick="onInviteAdd();" class="btn green-btn mr-10">{{ __('Save') }}</a>
						<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
					</center>
				</div>
			</form>

			<!-- Modal body -->
			<div class="modal-body">
				<h5>{{ __('Staff/Client List') }}</h5>
				<div class="table-responsive-md">
					<input class="form-control mb-10" id="staff_search_id" type="text" placeholder="Search..">
					<table class="table table-bordered" width="100%">
						<thead>
						<tr>
							<th scope="col" width="10%">{{ __('Photo') }}</th>
							<th scope="col" width="80%">{{ __('Staff/Client') }}</th>
							<th scope="col" width="10%" class="text-center">{{ __('Active') }}</th>
							<th scope="col" width="10%" class="text-center">{{ __('Delete') }}</th>
						</tr>
						</thead>
						<tbody id="staff_list_id"></tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /Invite Modal -->

<!-- Project Info Modal -->
<div class="modal fade" id="View_Id">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Project Details') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body pt-0">
				<div class="table-responsive-md">
					<table class="table table-bordered" width="100%">
						<tbody id="project_info"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Project Info Modal -->
@endsection

@push('scripts')
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var role = "{{ Auth::user()->role }}";
var userid = "{{ Auth::user()->id }}";
var TEXT = [];
	TEXT['View'] = "{{ __('View') }}";
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['No data available'] = "{{ __('No data available') }}";
	TEXT['Go To Task Board'] = "{{ __('Go To Task Board') }}";
	TEXT['Invite to project'] = "{{ __('Invite to project') }}";
	TEXT['Milestones'] = "{{ __('Milestones') }}";
	TEXT['Project Name'] = "{{ __('Project Name') }}";
	TEXT['Start Date'] = "{{ __('Start Date') }}";
	TEXT['End Date'] = "{{ __('End Date') }}";
	TEXT['Client'] = "{{ __('Client') }}";
	TEXT['Budget'] = "{{ __('Budget') }}";
	TEXT['Description'] = "{{ __('Description') }}";
	TEXT['Currency'] = "{{ __('Currency') }}";
	TEXT['Status'] = "{{ __('Status') }}";
	TEXT['Select staff'] = "{{ __('Select staff') }}";
	TEXT['Active'] = "{{ __('Active') }}";
	TEXT['Inactive'] = "{{ __('Inactive') }}";
	TEXT['Select staff/client'] = "{{ __('Select staff/client') }}";
	TEXT['Staff'] = "{{ __('Staff') }}";
	TEXT['Admin'] = "{{ __('Admin') }}";
</script>
<!-- projects js -->
<script src="{{asset('public/pages/projects.js')}}"></script>
@endpush
