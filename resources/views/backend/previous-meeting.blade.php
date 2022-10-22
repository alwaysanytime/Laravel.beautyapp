@extends('layouts.backend')

@section('title', __('Previous Meeting'))

@section('content')
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
					<div class="card-header">{{ __('Zoom Meeting') }}</div>
					<div class="card-body tabs-area p-0">
						<ul class="tabs-nav">
							<li><a id="tabId-1" href="{{ route('backend.upcoming-meeting') }}"><i class="fa fa-exchange"></i>{{ __('Upcoming Meeting') }}</a></li>
							<li><a class="active" id="tabId-2" href="{{ route('backend.previous-meeting') }}"><i class="fa fa-exchange"></i>{{ __('Previous Meeting') }}</a></li>
							<li><a id="tabId-3" href="{{ route('backend.live-meeting') }}"><i class="fa fa-exchange"></i>{{ __('Live Meeting') }}</a></li>
							<li><a id="tabId-4" href="{{ route('backend.zoom-settings') }}"><i class="fa fa-cog"></i>{{ __('Zoom Settings') }}</a></li>
						</ul>
						<div class="tabs-body">
							<!--/Data grid-->
							<div id="list-panel" class="mt-20">
								<div class="row">
									<div class="col-lg-12">
										<div class="table-responsive">
											<input class="form-control mb-10" id="Meeting_TableId_filter" type="text" placeholder="Search..">
											<table id="Meeting_TableId" class="table table-striped table-bordered">
												<thead>
													<tr>
														<th class="text-center" width="5%">{{ __('SL') }}</th>
														<th width="50%">{{ __('Meeting') }}</th>
														<th class="text-center" width="17%">{{ __('Invitation') }}</th>
														<th class="text-center" width="18%">{{ __('Join Meeting') }}</th>
														<th class="text-center" width="10%">{{ __('Action') }}</th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div><!--/Data grid-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /main Section -->

<!-- Meeting Invitation Modal -->
<div class="modal fade" id="Meeting_Invitation_Id">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form id="MeetingInvitation_formId">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">{{ __('Meeting Invitation') }}</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12" id="CopyInvitation">
						</div>
					</div>
					<div class="form-group">
						<label for="StaffClient_id"><span class="red">*</span> {{ __('Staff/Client') }}</label>
						<select name="StaffClient_id" id="StaffClient_id" class="chosen-select form-control" tabindex="-1">
						</select>
					</div>
					
					<input type="text" name="Invitation_Meeting_Topic" id="Invitation_Meeting_Topic" class="dnone">
					<input type="text" name="Invitation_Time" id="Invitation_Time" class="dnone">
					<input type="text" name="Invitation_Timezone" id="Invitation_Timezone" class="dnone">
					<input type="text" name="Invitation_join_url" id="Invitation_join_url" class="dnone">
					<input type="text" name="Invitation_password" id="Invitation_password" class="dnone">
					<center>
						<a href="javascript:void(0);" onclick="onAddMeetingInvitation();" class="btn green-btn mr-10">{{ __('Invitation') }}</a>
						<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
					</center>
				</div>
			</form>
			
			<!-- Modal body -->
			<div class="modal-body">
				<h5>{{ __('Staff/Client List') }}</h5>
				<div class="table-responsive-md">
					<input class="form-control mb-10" id="staff_client_search_id" type="text" placeholder="Search..">
					<table class="table table-bordered" width="100%">
						<thead>
						<tr>
							<th scope="col" width="10%">{{ __('Photo') }}</th>
							<th scope="col" width="80%">{{ __('Staff/Client') }}</th>
							<th scope="col" width="10%" class="text-center">{{ __('Delete') }}</th>
						</tr>
						</thead>
						<tbody id="staff_client_list_id"></tbody>
					</table>
				</div>
			</div>
			
		</div>
	</div>
</div>
<!-- /Meeting Invitation Modal -->
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
	TEXT['Select staff/client'] = "{{ __('Select staff/client') }}";
	TEXT['No data available'] = "{{ __('No data available') }}";
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Meeting Invitation'] = "{{ __('Meeting Invitation') }}";
	TEXT['Invitation'] = "{{ __('Invitation') }}";
	TEXT['Start Meeting'] = "{{ __('Start Meeting') }}";
	TEXT['Start'] = "{{ __('Start') }}";
	TEXT['Meeting Topic'] = "{{ __('Meeting Topic') }}";
	TEXT['Meeting ID'] = "{{ __('Meeting ID') }}";
	TEXT['Start Time'] = "{{ __('Start Time') }}";
	TEXT['Time Zone'] = "{{ __('Time Zone') }}";
	TEXT['Time'] = "{{ __('Time') }}";
	TEXT['Join Zoom Meeting'] = "{{ __('Join Zoom Meeting') }}";
	TEXT['Passcode'] = "{{ __('Passcode') }}";
	TEXT['Duration'] = "{{ __('Duration') }}";
	TEXT['minutes'] = "{{ __('minutes') }}";
	TEXT['Join Meeting'] = "{{ __('Join Meeting') }}";
</script>

<!--js -->
<script src="{{asset('public/pages/previous_meeting.js')}}"></script>
<script src="{{asset('public/pages/zoom_meeting.js')}}"></script>
@endpush