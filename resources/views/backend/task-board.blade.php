@extends('layouts.backend')

@section('title', __('Task Board'))

@section('content')
<!-- main Section -->
<div class="main-body" id="tw-loader">
	<div class="container-fluid">
		<div class="row">
			<div class="tw-loader">
				<div class="tw-ellipsis">
					<div></div><div></div><div></div><div></div>
				</div>						
			</div>
		</div>
	</div>
</div>
<div class="main-body dnone" id="data_empty">
	<div class="container-fluid">
		<div class="row">
			<div class="col" id="error_msg"></div>
		</div>
	</div>
</div>

<div class="main-body dnone" id="data_rows">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<div class="be-heading mb-10">
					<div class="row">
						<div class="col-lg-9 col-md-8">
							<a href="{{ route('backend.milestones', [$project_id]) }}" class="btn green-btn" style="padding:5px 15px;"><i class="fa fa-paper-plane-o"></i> {{ __('Milestones View') }}</a>
							<h4 class="mt-10" id="project_name_id"></h4>
							<ul class="invite_staff_list" id="inviteStaffList"></ul>
						</div>
						<div class="col-lg-3 col-md-4 mt-20 mb-10">
							<a data-toggle="modal" data-target="#AddNewListId" href="javascript:void(0);" class="btn green-btn btn-form fl-right"><i class="fa fa-plus"></i> {{ __('Add New List') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Invite to project Modal -->
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
								<label for="invite_staff_id"><span class="red">*</span> {{ __('Staff/Client') }}</label>
								<select name="staff_id" id="invite_staff_id" class="chosen-select form-control" tabindex="2">
								</select>
							</div>
							<input type="text" name="userid" class="dnone" value="{{ Auth::user()->id }}">
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
							<input class="form-control mb-10" id="invite_staff_search_id" type="text" placeholder="Search..">
							<table class="table table-bordered" width="100%">
								<thead>
								<tr>
									<th scope="col" width="10%">{{ __('Photo') }}</th>
									<th scope="col" width="80%">{{ __('Staff/Client') }}</th>
									<th scope="col" width="10%" class="text-center">{{ __('Active') }}</th>
									<th scope="col" width="10%" class="text-center">{{ __('Delete') }}</th>
								</tr>
								</thead>
								<tbody id="invite_staff_list_id"></tbody>
							</table>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<!-- /Invite to project Modal -->
		
		<!--/Data grid-->
		<div class="row mt-10">
			<div class="tasks-board owl-carousel datalist"></div>
		</div>
		<!--/Data grid-->
	</div>
</div>
<!-- /main Section -->

<!-- Task Group List Modal -->
<div class="modal fade" id="AddNewListId">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Add New List') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<form novalidate="" data-validate="parsley" id="DataEntry_formId">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="task_group_name"><span class="red">*</span> {{ __('New List Name') }}</label>
									<input type="text" name="task_group_name" id="task_group_name" class="form-control parsley-validated" data-required="true">
								</div>
							</div>
						</div>
					</div>
					<input type="text" id="project_id" name="project_id" class="dnone" value="{{ $project_id }}"/>
					<input type="text" id="RecordId" name="RecordId" class="dnone"/>
					<center>
						<a id="submit-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
						<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Task Group List Modal -->
	
<!-- Add a task Modal -->
<div class="modal fade" id="AddaTaskId">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Task Form') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<form novalidate="" data-validate="parsley" id="AddaTask_formId">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="task_name"><span class="red">*</span> {{ __('Task Name') }}</label>
									<input type="text" name="task_name" id="task_name" class="form-control parsley-validated" data-required="true">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="task_date"><span class="red">*</span> {{ __('Date') }}</label>
									<input type="text" name="task_date" id="task_date" class="form-control parsley-validated" data-required="true" placeholder="yyyy-mm-dd hh:ii:ss">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="description">{{ __('Description') }}</label>
									<textarea name="description" id="description" class="form-control" rows="3"></textarea>
								</div>
							</div>
						</div>
						<input type="text" name="userid" class="dnone" value="{{ Auth::user()->id }}">
						<input type="text" id="task_id" name="task_id" class="dnone" />
						<center>
							<a id="addatask-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
							<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
						</center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add a task Modal -->
	
<!-- Task Status Modal -->
<div class="modal fade" id="TaskStatusId">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Change Task Status') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<form novalidate="" data-validate="parsley" id="TaskStatus_formId">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="status_task_date"><span class="red">*</span> {{ __('Date') }}</label>
									<input type="text" name="status_task_date" id="status_task_date" class="form-control parsley-validated" data-required="true" placeholder="yyyy-mm-dd hh:ii:ss">
								</div>
							</div>
						</div>
						<div class="row mb-20">
							<div class="col-lg-12">
								<div class="tw_checkbox">
									<input name="complete_task" id="complete_task" type="checkbox">
									<label for="complete_task">{{ __('Task is completed') }}</label>
									<span></span>
								</div>
							</div>
						</div>
						<input type="text" name="userid" class="dnone" value="{{ Auth::user()->id }}">
						<input type="text" id="status_task_id" name="status_task_id" class="dnone" />
						<center>
							<a id="TaskStatus-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
							<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
						</center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Task Status Modal -->
	
<!-- Task Move Modal -->
<div class="modal fade" id="TaskMoveId">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Move the task') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<form id="TaskMove_formId">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<select name="move_task_group_id" id="move_task_group_id" class="chosen-select form-control" tabindex="2">
									</select>
								</div>
							</div>
						</div>
						<input type="text" name="userid" class="dnone" value="{{ Auth::user()->id }}">
						<input type="text" name="Move_task_id" id="Move_task_id" class="dnone">
						<center>
							<a onClick="onUpdateTaskMove();" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
							<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
						</center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Task Move Modal -->

<!-- Invite Task Modal -->
<div class="modal fade" id="InviteTask_Id">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="InviteTask_formId">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">{{ __('Invite to Task') }}</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<div class="form-group">
						<label for="staff_id"><span class="red">*</span> {{ __('Staff') }}</label>
						<select name="staff_id" id="staff_id" class="chosen-select form-control" tabindex="2">
						</select>
					</div>
					<input type="text" name="userid" class="dnone" value="{{ Auth::user()->id }}">
					<input type="text" id="invite_task_id" name="invite_task_id" class="dnone"/>
					<center>
						<a href="javascript:void(0);" onclick="onInviteTaskAdd();" class="btn green-btn mr-10">{{ __('Save') }}</a>
						<a href="javascript:void(0);" class="btn danger-btn" data-dismiss="modal">{{ __('Cancel') }}</a>
					</center>
				</div>
			</form>
			
			<!-- Modal body -->
			<div class="modal-body">
				<h5>{{ __('Staff List') }}</h5>
				<div class="table-responsive-md">
					<input class="form-control mb-10" id="staff_search_id" type="text" placeholder="Search..">
					<table class="table table-bordered" width="100%">
						<thead>
						<tr>
							<th scope="col" width="10%">{{ __('Photo') }}</th>
							<th scope="col" width="80%">{{ __('Staff Name') }}</th>
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
<!-- /Invite Task Modal -->

<!-- Add a Comments Attachment Modal -->
<div class="modal fade bd-example-modal-lg" id="CommentsAttachmentId">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ __('Task Activity') }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row mb-10">
						<div class="col-lg-12">
							<div id="back_task_name"></div>
							<small class="text-muted" id="back_group_name"></small>
						</div>
					</div>
					<form novalidate="" data-validate="parsley" id="Comments_formId">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="comments">{{ __('Comments') }}</label>
									<textarea name="comments" id="comments" class="form-control comments parsley-validated" data-required="true"></textarea>
								</div>
								<div class="form-group mb-10">
									<p id="attach-file-name"></p>
									<label class="attachment"><a onclick="onNewAttachForm('');" href="javascript:void(0);"><i class="fa fa-paperclip"></i> {{ __('Attachment') }}</a></label>
								</div>
								<input type="text" id="attachment-files" name="attachment-files" class="dnone" />
								<input type="text" id="comments_id" name="comments_id" class="dnone" />
								<a id="addComments-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
							</div>
						</div>
					</form>
				</div>
				<div class="clearfix"></div>
				<div class="container-fluid" id="comments-list">
					<div class="row">
						<div class="col-md-12 mb-10">			
							<ul class="list-unstyled activity-list"></ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Add a Comments Attachment Modal -->			

<!-- Attachment upload Modal -->
<div id="attach_form_id" class="card tw-popup-card dnone">
	<div class="card-header">
		<h4 class="modal-title">{{ __('Attachment Form') }}</h4>
		<button class="popup_close attach_form_id_close" aria-label="Close">&times;</button>
	</div>
	<div class="card-body">
		<form id="attach_formId">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<input type="file" name="FileName[]" id="FileName" class="attach-file" multiple="multiple">
							<label for="FileName" class="attachment-file" id="attachment-file">
								<i class="fa fa-cloud-upload"></i>
								<h5 class="filecount"></h5>
							</label>
							<div class="upload-error"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="progress mb-20" id="progress-wrp">
						<div class="progress-bar progress-bar-bg"></div>
					</div>
				</div>
				</div>
			</div>
			<center>
				<a onclick="upload_Form();" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Upload') }}</a>
				<a href="javascript:void(0);" class="btn danger-btn attach_form_id_close">{{ __('Cancel') }}</a>
			</center>
		</form>
	</div>
</div>
<!-- /Attachment upload Modal -->
@endsection

@push('scripts')
<!-- owl.carousel css/js -->
<link rel="stylesheet" href="{{asset('public/assets/css/owl.carousel.min.css')}}">
<script src="{{asset('public/assets/js/owl.carousel.min.js')}}"></script>
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var project_id = "{{ $project_id }}";
var userid = "{{ Auth::user()->id }}";
var TEXT = [];
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Add a task'] = "{{ __('Add a task') }}";
	TEXT['No data available'] = "{{ __('No data available') }}";
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['You did not activated this project'] = "{{ __('You did not activated this project') }}";
	TEXT['You are not connected in this project'] = "{{ __('You are not connected in this project') }}";
	TEXT['Select staff'] = "{{ __('Select staff') }}";
	TEXT['Active'] = "{{ __('Active') }}";
	TEXT['Inactive'] = "{{ __('Inactive') }}";
	TEXT['Attachment'] = "{{ __('Attachment') }}";
	TEXT['Save'] = "{{ __('Save') }}";
	TEXT['Cancel'] = "{{ __('Cancel') }}";
	TEXT['Please choose the files to upload'] = "{{ __('Please choose the files to upload') }}";
	TEXT['Total Records'] = "{{ __('Total Records') }}";
	TEXT['Invite to project'] = "{{ __('Invite to project') }}";
	TEXT['Select staff/client'] = "{{ __('Select staff/client') }}";
	TEXT['Admin'] = "{{ __('Admin') }}";
	TEXT['Client'] = "{{ __('Client') }}";
	TEXT['Staff'] = "{{ __('Staff') }}";
</script>
<!-- task-board js -->
<script src="{{asset('public/pages/task-board.js')}}"></script>
@endpush