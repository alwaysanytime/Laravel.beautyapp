@extends('layouts.backend')

@section('title', __('Language Keywords'))

@section('content')
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
					<div class="card-header">{{ __('Languages') }}</div>
					<div class="card-body tabs-area p-0">
						<ul class="tabs-nav">
							<li><a id="tabId-1" href="{{ route('backend.languages') }}"><i class="fa fa-exchange"></i>{{ __('Languages') }}</a></li>
							<li><a class="active" id="tabId-2" href="{{ route('backend.language-keywords') }}"><i class="fa fa-exchange"></i>{{ __('Language Keywords') }}</a></li>
						</ul>
						<div class="tabs-body">
							<div class="tabs-head">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group mb-10 filter">
											<select name="language_code" id="language_code" class="form-control"></select>
										</div>
									</div>
									<div class="col-md-9">
										<div class="float-right">
											<a onClick="onFormPanel()" href="javascript:void(0);" class="btn green-btn btn-form fl-right"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
											<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list fl-right dnone"><i class="fa fa-plus"></i> {{ __('Back to List') }}</a>
										</div>
									</div>
								</div>
							</div>
							<!--/Data grid-->
							<div id="list-panel">
								<div class="row">
									<div class="col-lg-12">
										<div class="table-responsive">
											<table id="DataTableId" class="table table-striped table-bordered">
												<thead>
													<tr>
														<th class="text-center" width="5%">{{ __('SL') }}</th>
														<th width="40%">{{ __('Language Key') }}</th>
														<th width="43%">{{ __('Language Value') }}</th>
														<th width="12%">{{ __('Action') }}</th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div><!--/Data grid-->
							<!--/Data Entry Form-->
							<div id="form-panel" class="dnone">
								<form novalidate="" data-validate="parsley" id="DataEntry_formId">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="language_key"><span class="red">*</span> {{ __('Language Key') }}</label>
												<input type="text" name="language_key" id="language_key" class="form-control parsley-validated" data-required="true">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="language_value"><span class="red">*</span> {{ __('Language Name') }}</label>
												<input type="text" name="language_value" id="language_value" class="form-control parsley-validated" data-required="true">
											</div>
										</div>
									</div>
									<input type="text" name="RecordId" id="RecordId" class="dnone">

									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="submit-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
											<a onClick="onListPanel()" href="javascript:void(0);" class="btn danger-btn">{{ __('Cancel') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Data Entry Form-->
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
<!-- datatables css/js -->
<link rel="stylesheet" href="{{asset('public/assets/datatables/dataTables.bootstrap4.min.css')}}">
<script src="{{asset('public/assets/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/datatables/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var userid = "{{ Auth::user()->id }}";
var TEXT = [];
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Edit'] = "{{ __('Edit') }}";
</script>
<script src="{{asset('public/pages/languages-keywords.js')}}"></script>
@endpush