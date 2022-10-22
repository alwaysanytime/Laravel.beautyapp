"use strict";

var $ = jQuery.noConflict();
var task_group_id = '';
var gTaskGroupId = '';
var RecordId = '';
var gComments_id = '';
var gTask_id = '';
var gAttachment_id = '';

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

function onResetCommentsForm() {
	$('.parsley-error-list').hide();
    resetForm("Comments_formId");
}

/*Project Info*/
function onProjectInfo() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getProjectInfo',
		data: 'project_id='+project_id+'&userid='+userid,
		success: function (response) {
			var data = response;

			var error_msg = '';
			if(data.length>0){
				var project_name = '';
				$.each(data, function (key, obj) {
					if(obj.project_name != null){
						project_name = obj.project_name;
					}else{
						project_name = '';
					}
					
					if(obj.bActive == 1){
						$("#data_empty").hide();
						$("#data_rows").show();
						error_msg = '';
					}else{
						$("#data_rows").hide();
						$("#data_empty").show();
						error_msg = '<div class="alert alert-warning" role="alert">'+TEXT['You did not activated this project']+'</div>';
					}
				});
				
				$("#error_msg").html(error_msg);
				$("#project_name_id").text(project_name);
				
			}else{
				error_msg = '<div class="alert alert-warning" role="alert">'+TEXT['You are not connected in this project']+'</div>';
				$("#error_msg").html(error_msg);
				$("#data_rows").hide();
				$("#data_empty").show();
				$("#project_name_id").text('');
			}
        }
    });
}

function onTaskGroup() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTaskGroup',
		data: 'project_id='+project_id,
		success: function (response) {		
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.task_group_name + '</option>';
			});
			
			$("#move_task_group_id").html(html);
			$("#move_task_group_id").chosen();
			$("#move_task_group_id").trigger("chosen:updated");
        }
    });
}

function onStaff(project_id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getStaffbyProject',
		data: 'project_id='+project_id,
		success: function (response) {
			var datalist = response;
			var html = '';
				html = '<option value="">' + TEXT['Select staff/client']+ '</option>';
			$.each(datalist, function (key, obj) {
				if(obj.role_id == 1){
					html += '<option value="' + obj.id + '">' + obj.name +' - '+TEXT['Admin']+'</option>';
				 }else if(obj.role_id == 2){
					html += '<option value="' + obj.id + '">' + obj.name +' - '+TEXT['Client']+'</option>';
				}else if(obj.role_id == 3){
					html += '<option value="' + obj.id + '">' + obj.name +' - '+TEXT['Staff']+'</option>';
				}
			});
			
			$("#invite_staff_id").html(html);
			$("#invite_staff_id").chosen();
			$("#invite_staff_id").trigger("chosen:updated");
        }
    });
}

function onInviteStaffPhoto() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getActiveStaffinProjects',
		data: 'project_id='+project_id,
		success: function (response) {	
			var data = response;

			var inviteStaffList = '<li class="staff-plus"><a onclick="onInviteData();" href="javascript:void(0);" title="'+TEXT['Invite to project']+'"><i class="fa fa-user-plus"></i></a></li>';

			$.each(data, function (key, obj) {
				
				if(obj.photo != null){
					var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
				}else{
					var photo = '<img src="'+public_path+'/assets/images/default.png">';
				}
				
				if(obj.name != null){
					var name = obj.name;
				}else{
					var name = '';
				}
				
				if(obj.photo != null){
					inviteStaffList += '<li><img title="'+name+'" src="'+public_path+'/media/'+obj.photo+'"></li>';
				}else{
					inviteStaffList += '<li><img title="'+name+'" src="'+public_path+'/assets/images/default.png"></li>';
				}
			});
			
			$("#inviteStaffList").html(inviteStaffList);
        }
    });
}

function onInviteData() {
	onStaff(project_id);

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getInviteProjectsData',
		data: 'project_id='+project_id,
		success: function (response) {
			var data = response;
			var html = '';
			if(data.length>0){
				$.each(data, function (key, obj) {
					
					if(obj.photo != null){
						var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
					}else{
						var photo = '<img src="'+public_path+'/assets/images/default.png">';
					}
					
					if(obj.name != null){
						var name = obj.name;
					}else{
						var name = '';
					}
					
					if(obj.role_id == 1){
						var role_name = TEXT['Admin'];
					}else if(obj.role_id == 2){
						var role_name = TEXT['Client'];
					}else if(obj.role_id == 3){
						var role_name = TEXT['Staff'];
					}
					
					if(obj.bActive == 1){
						var active_id = '<td class="text-center"><a onclick="onInviteActive('+obj.id+','+obj.project_id+',0);" href="javascript:void(0)" title="'+TEXT['Active']+'" class="active_icon"><i class="fa fa-check"></i></a></td>';
					}else{
						var active_id = '<td class="text-center"><a onclick="onInviteActive('+obj.id+','+obj.project_id+',1);" href="javascript:void(0)" title="'+TEXT['Inactive']+'" class="inactive_icon"><i class="fa fa-times"></i></a></td>';
					}
					
					html += '<tr>'
						+'<td><span class="list-photo">'+photo+'</span></td>'
						+'<td>'+name+' - '+role_name+'</td>'
						+active_id
						+'<td class="text-center"><a onclick="onInviteDelete('+obj.id+','+obj.project_id+');" href="javascript:void(0)" title="'+TEXT['Delete']+'" class="delete_icon"><i class="fa fa-trash-o"></i></a></td>'
					+'</tr>';
				});
			}else{
				html = '<tr><td colspan="4"><div class="alert alert-warning" role="alert">'+TEXT['No data available']+'</div></td></tr>';
			}
			
			$("#invite_staff_list_id").html(html);
			$('#Invite_Id').modal('show');
        }
    });
}

function onInviteAdd() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveInviteData',
		data: $('#Invite_formId').serialize() + '&project_id='+project_id,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteData(project_id);
				onInviteStaffPhoto();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInviteActive(project_staff_id, project_id, bActive) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/InviteActiveInactive',
		data: 'id='+project_staff_id + '&bActive='+bActive + '&userid=' + userid,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteData(project_id);
				onInviteStaffPhoto();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInviteDelete(RecordId, project_id) {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteInviteProject',
		data: 'id='+RecordId,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteData(project_id);
				onInviteStaffPhoto();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

//Task Board
function onLoadTaskBoardData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTaskBoardData',
		data: 'project_id='+project_id,
		success: function (response) {
			var data = response;

			var html = '';
			if(data.length>0){
				
				$.each(data, function (key, obj) {
					
					if(obj.task_group_name != null){
						var task_group_name = obj.task_group_name;
					}else{
						var task_group_name = '';
					}
					
					html += '<div class="col-lg-12 tw_task_group" id="TaskBody_'+obj.id+'">'
						+'<div class="task-group">'
							+'<div class="task-header">'
							+'<p id="task_group_name_'+obj.id+'">'+task_group_name+'</p>'
							+'<ul class="head-action">'
								+'<li class="head-edit"><a onclick="onTaskBoardEditData('+obj.id+');" href="javascript:void(0);" title="'+TEXT['Edit']+'"><i class="fa fa-pencil"></i></a></li>'
								+'<li class="head-delete"><a onclick="onTaskBoardDelete('+obj.id+');" href="javascript:void(0)" title="'+TEXT['Delete']+'"><i class="fa fa-trash-o"></i></a></li>'
							+'</ul>'
							+'</div>'
							+'<div class="task-body">'
								+'<ul class="task-list" id="tasklist_'+obj.id+'">'
								+obj.TaskList
								+'</ul>'
							+'</div>'
							+'<div class="task-footer">'
								+'<a onClick="onAddTask('+obj.id+')" href="javascript:void(0);"><i class="fa fa-plus"></i>'+TEXT['Add a task']+'</a>'
							+'</div>'
						+'</div>'
					+'</div>';	
				});
			} else {
				html = '<div class="col-lg-12"><div class="alert alert-warning" role="alert">'+TEXT['No data available']+'</div></div>';
			}

			$("#tw-loader").hide();
			$(".datalist").html(html);
			$('.tasks-board').owlCarousel('destroy');
			onLoadLibraryJS();
        }
    });
}

jQuery('#DataEntry_formId').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            else {
                showPerslyError();
                return false;
            }
        },
        onFormSubmit: function (isFormValid, event) {
            if (isFormValid) {
                onTaskBoardAddEdit();
                return false;
            }
        }
    }
});

function onTaskBoardAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveTaskBoardData',
		data: $('#DataEntry_formId').serialize()+'&project_id='+project_id,
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onLoadTaskBoardData();
				onLoadLibraryJS();
				resetForm("DataEntry_formId");
				$('#AddNewListId').modal('hide');
				onSuccessMsg(msg);
				onTaskGroup();
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onTaskBoardEditData(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to edit this record"];
	onCustomModal(msg, "onLoadTaskBoardEditData");	
}

function onLoadTaskBoardEditData() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTaskBoardById',
		data: 'RecordId='+RecordId,
		success: function (response) {			
			var data = response;
			$("#RecordId").val(data.id);
			$("#task_group_name").val(data.task_group_name);
			$('#AddNewListId').modal('show');
        }
    });
}

function onTaskBoardDelete(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmTaskBoardDelete");	
}

function onConfirmTaskBoardDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteTaskBoard',
		data: 'RecordId='+RecordId,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onSuccessMsg(msg);
				resetForm("DataEntry_formId");
				$('#AddNewListId').modal('hide');
				onLoadTaskBoardData();
				onLoadLibraryJS();
				onTaskGroup();
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

//Task
function onAddTask(task_group_id) {
	gTaskGroupId = task_group_id;
	resetForm("AddaTask_formId");
	$('#AddaTaskId').modal('show');
}

jQuery('#AddaTask_formId').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            else {
                showPerslyError();
                return false;
            }
        },
        onFormSubmit: function (isFormValid, event) {
            if (isFormValid) {
                onAddaTaskAddEdit();
                return false;
            }
        }
    }
});

function onAddaTaskAddEdit() {
	var task_group_name = $("#task_group_name_"+gTaskGroupId).text();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveTaskData',
		data: $('#AddaTask_formId').serialize()
			+'&task_group_id='+gTaskGroupId
			+'&task_group_name='+task_group_name
			+'&project_id='+project_id,
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onLoadTaskBoardData();
				onLoadLibraryJS();
				resetForm("AddaTask_formId");
				$('#AddaTaskId').modal('hide');
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onTaskEditData(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to edit this record"];
	onCustomModal(msg, "onLoadTaskEditData");	
}

function onLoadTaskEditData() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTaskById',
		data: 'RecordId='+RecordId,
		success: function (response) {			
			var data = response;
			
			gTaskGroupId = data.task_group_id;
			$("#task_name").val(data.task_name);
			$('#task_date').val(data.task_date).datetimepicker('update');
			$("#description").val(data.description);
			$("#task_id").val(data.id);

			$('#AddaTaskId').modal('show');
        }
    });
}

function onTaskDelete(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmTaskDelete");	
}

function onConfirmTaskDelete() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteTask',
		data: 'RecordId='+RecordId,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onLoadTaskBoardData();
				onLoadLibraryJS();
				onSuccessMsg(msg);
				
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onLoadTaskDataForStatus(task_id) {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTaskById',
		data: 'RecordId='+task_id,
		success: function (response) {
			var data = response;
			
			gTaskGroupId = data.task_group_id;
			$('#status_task_date').val(data.task_date).datetimepicker('update');
			$("#status_task_id").val(data.id);

			if(data.complete_task == 1){
				$("#complete_task").prop("checked", true);
			}else{
				$("#complete_task").prop("checked", false);
			}
			
			$('#TaskStatusId').modal('show');
        }
    });
}

jQuery('#TaskStatus_formId').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            else {
                showPerslyError();
                return false;
            }
        },
        onFormSubmit: function (isFormValid, event) {
            if (isFormValid) {
                onTaskStatus();
                return false;
            }
        }
    }
});

function onTaskStatus() {
	
	var checked = document.getElementById('complete_task').checked;
	if(checked == true){
		var complete_task = 1;
	}else{
		var complete_task = 0;
	}
	
	var task_group_name = $("#task_group_name_"+gTaskGroupId).text();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/updateTaskStatusData',
		data: $('#TaskStatus_formId').serialize()
				+ '&task_group_name='+task_group_name
                + '&task_group_id='+gTaskGroupId
                + '&status_complete_task='+complete_task
				+ '&project_id='+project_id,
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onLoadTaskBoardData();
				onLoadLibraryJS();
				$('#TaskStatusId').modal('hide');
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

//End of Task
function onMoveTask(task_id, task_group_id) {
	gTaskGroupId = task_group_id;
	$("#move_task_group_id").val(gTaskGroupId).trigger("chosen:updated");
	$('#Move_task_id').val(task_id);
	$('#TaskMoveId').modal('show');
}

function onUpdateTaskMove() {
	
	var task_group_name = $("#task_group_name_"+gTaskGroupId).text();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/updateTaskMove',
		data: $('#TaskMove_formId').serialize() + '&task_group_name='+task_group_name+'&project_id='+project_id,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onLoadTaskBoardData();
				onLoadLibraryJS();
				$('#TaskMoveId').modal('hide');
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

//End of Task

//Invite Task
function onInviteTask(id) {

	$('#invite_task_id').val(id);
	onInviteStaff(id);

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getInviteTaskData',
		data: 'project_id='+project_id + '&task_id='+id,
		success: function (response) {
			
			var data = response;
			var html = '';
			if(data.length>0){
				$.each(data, function (key, obj) {

					if(obj.photo != null){
						var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
					}else{
						var photo = '<img src="'+public_path+'/assets/images/default.png">';
					}
					
					if(obj.name != null){
						var name = obj.name;
					}else{
						var name = '';
					}
					
					html += '<tr>'
						+'<td><span class="list-photo">'+photo+'</span></td>'
						+'<td>'+name+'</td>'
						+'<td class="text-center"><a onclick="onInviteTaskDelete('+obj.id+','+obj.task_id+');" href="javascript:void(0)" title="'+TEXT['Delete']+'" class="delete_icon"><i class="fa fa-trash-o"></i></a></td>'
					+'</tr>';
				});
			}else{
				html = '<tr><td colspan="3"><div class="alert alert-warning" role="alert">'+TEXT['No data available']+'</div></td></tr>';
			}
			
			$("#staff_list_id").html(html);
        }
    });
	
	$('#InviteTask_Id').modal('show');
}

//Invite to project
function onInviteStaff(task_id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getInviteStaff',
		data: 'project_id='+project_id + '&task_id='+task_id,
		success: function (response) {
			
			var datalist = response;
			var html = '';
				html = '<option value="">' + TEXT['Select staff']+ '</option>';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.name + '</option>';
			});
			
			$("#staff_id").html(html);
			$("#staff_id").chosen();
			$("#staff_id").trigger("chosen:updated");
        }
    });
}

function onInviteTaskAdd() {
	
	var task_id = $('#invite_task_id').val();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/insertInviteTaskData',
		data: $('#InviteTask_formId').serialize()+'&project_id='+project_id,
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteTask(task_id);
				onLoadTaskBoardData();
				onLoadLibraryJS();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInviteTaskDelete(RecordId, task_id) {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteInviteTask',
		data: 'RecordId=' + RecordId,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteTask(task_id);
				onLoadTaskBoardData();
				onLoadLibraryJS();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}
//End of Invite Task

//Comments Attachment
function onCommentsAttachment(task_id, task_group_id) {
	
	onResetCommentsForm();
	$("#attach-file-name").text('');
	gTaskGroupId = task_group_id;
	gTask_id = task_id;

	var group_name = $("#task_group_name_"+task_group_id).text();
	var task_name = $("#task_name_"+task_id).text();
	$("#back_task_name").text(task_name);
	$("#back_group_name").text('in list '+group_name);

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getCommentsData',
		data: 'task_id=' + task_id,
		success: function (response) {	
			var data = response;
			var html = '';
			
			$.each(data, function (key, obj) {

				if(obj.name != null){
					var name = obj.name;
				}else{
					var name = '';
				}
				
				if(obj.photo != null){
					var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
				}else{
					var photo = '<img src="'+public_path+'/assets/images/default.png">';
				}
				
				if(obj.comment != null){
					var comment = obj.comment;
				}else{
					var comment = '';
				}
				if(obj.comments_date != null){
					var comments_date = obj.comments_date;
				}else{
					var comments_date = '';
				}
				
				if(obj.battach == 1){
					var attachment = '<h5 class="attach-title"><a onclick="onAttachForm('+obj.task_id+','+obj.id+');" href="javascript:void(0);" title="'+TEXT['Attachment']+'"><i class="fa fa-paperclip"></i> '+TEXT['Attachment']+'</a></h5>'
									+ '<ul class="attachment-list">'+obj.attachment+'</ul>';
				}else{
					var attachment = '';
				}
				if(obj.editable == 1){
					var commentsControl = '<ul class="comments-control" id="comments-control_'+obj.id+'">'
							+ '<li class="com-edit"><a onclick="onCommentEditData('+obj.id+');" href="javascript:void(0);" title="'+TEXT['Edit']+'"><i class="fa fa-pencil"></i></a></li>'
							+ '<li class="com-edit"><a onclick="onAttachForm('+obj.task_id+','+obj.id+');" href="javascript:void(0);" title="'+TEXT['Attachment']+'"><i class="fa fa-paperclip"></i></a></li>'
							+ '<li class="com-delete"><a onclick="onCommentDelete('+obj.task_id+','+obj.id+');" href="javascript:void(0);" title="'+TEXT['Delete']+'"><i class="fa fa-trash-o"></i></a></li>'
						+ '</ul>'
				}else{
					var commentsControl = '';
				}
				
				html += '<li class="media my-4">'
					+ photo
					+ '<div class="media-body">'
						+ '<h5 class="mt-0 mb-1">'
							+ '<span>'+name+'</span>'
							+ commentsControl
						+ '</h5>'
						+ '<div class="row display-none" id="comments_box_'+obj.id+'">'
							+ '<div class="col-lg-12">'
								+ '<div class="form-group edit-textarea">'
									+ '<textarea name="comments" id="comments_txt_'+obj.id+'" class="form-control"></textarea>'
								+ '</div>'
								+ '<a onclick="onCommentsEditSave('+obj.task_id+','+obj.id+');" href="javascript:void(0);" class="btn green-btn mr-10">'+TEXT['Save']+'</a>'
								+ '<a onClick="onCommentsBoxsh('+obj.id+')" href="javascript:void(0);" class="btn danger-btn">'+TEXT['Cancel']+'</a>'
							+ '</div>'
						+ '</div>'
						+ '<div class="info" id="geTcommentsTxt_'+obj.id+'">' +comment+ '</div>'
						+ '<span class="text-muted" id="text-muted_'+obj.id+'">'
							+ '<small class="text-muted">'+comments_date+'</small>'
						+ '</span>'
						+ attachment
					+ '</div>'
				  + '</li>';
			});
		
			$(".activity-list").html(html);
			$("#comments-list").show();
			$('#CommentsAttachmentId').modal('show');
        }
    });
}

jQuery('#Comments_formId').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            else {
                showPerslyError();
                return false;
            }
        },
        onFormSubmit: function (isFormValid, event) {
            if (isFormValid) {
                onCommentsAddEdit();
                return false;
            }
        }
    }
});

function onCommentsAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/insertUpdateComments',
		data: $('#Comments_formId').serialize() + '&userid='+userid + '&task_id='+gTask_id+'&project_id='+project_id,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onCommentsAttachment(gTask_id, gTaskGroupId);
				resetForm("Comments_formId");
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onCommentEditData(comments_id) {
	var commentsTxt = $("#geTcommentsTxt_"+comments_id).text();
	$("#comments_txt_"+comments_id).val(commentsTxt);
	
	$("#comments-control_"+comments_id).hide();
	$("#text-muted_"+comments_id).hide();
	$("#geTcommentsTxt_"+comments_id).hide();
	$("#comments_box_"+comments_id).show();
}

function onCommentsBoxsh(comments_id) {
	$("#comments_box_"+comments_id).hide();
	$("#comments_txt_"+comments_id).val('');
	$("#comments-control_"+comments_id).show();
	$("#text-muted_"+comments_id).show();
	$("#geTcommentsTxt_"+comments_id).show();
}

function onCommentsEditSave(task_id, comments_id) {

	var commentsTxt = $("#comments_txt_"+comments_id).val();

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/updateComments',
		data: 'userid='+userid + '&comments_id='+comments_id + '&comment='+commentsTxt,
		success: function (response) {			
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onCommentsBoxsh(comments_id);
				onCommentsAttachment(task_id, gTaskGroupId);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onCommentDelete(task_id, comments_id) {
	gTask_id = task_id;
	gComments_id = comments_id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmCommentDelete");	
}

function onConfirmCommentDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteComment',
		data: 'comments_id='+gComments_id,
		success: function (response) {			
            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				onSuccessMsg(msg);
				onCommentsAttachment(gTask_id, gTaskGroupId);
				
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onAttachDelete(task_id, attachment_id) {
	gTask_id = task_id;
	gAttachment_id = attachment_id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmAttachDelete");	
}

function onConfirmAttachDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteAttach',
		data: 'attachment_id='+gAttachment_id,
		success: function (response) {	
            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				onSuccessMsg(msg);
				onCommentsAttachment(gTask_id, gTaskGroupId);
				
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onNewAttachForm(comments_id) {
	resetForm("attach_formId");
	gComments_id = comments_id;
	$(".filecount").text('');
	$(".upload-error").html('');
	$("#progress-wrp").hide();
	$('.progress-bar').css("width", "0%");
	$('.progress-bar').text("0%");
	$('#attach_form_id').popup('show');
}

function onAttachForm(task_id, comments_id) {
	resetForm("attach_formId");
	gTask_id = task_id;
	gComments_id = comments_id;
	$(".filecount").text('');
	$(".upload-error").html('');
	$("#progress-wrp").hide();
	$('.progress-bar').css("width", "0%");
	$('.progress-bar').text("0%");
	$('#attach_form_id').popup('show');
}

function upload_Form() {

	var data = new FormData();
		var fileCount = document.getElementById('FileName').files.length;
		
		if(fileCount == 0){
			$(".upload-error").html('<p class="tw-alert-txt">'+TEXT['Please choose the files to upload']+'</p>');
			return;
		}else{
			$(".upload-error").html('');
		}
		
		for (var x = 0; x < fileCount; x++) {
			data.append("FileName[]", document.getElementById('FileName').files[x]);
		}
		var ReaderObj = new FileReader();

	$.ajax({
		url: base_url + '/backend/attachmentUpload',
		type: "POST",
		data: data,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false,
		xhr: function(){
			jQuery('#progress-wrp').show();
			var xhr = $.ajaxSettings.xhr();
			if (xhr.upload) {
				xhr.upload.addEventListener('progress', function(event) {
					var percent = 0;
					var position = event.loaded || event.position;
					var total = event.total;
					if (event.lengthComputable) {
						percent = Math.ceil(position / total * 100);
					}
					$('.progress-bar').css("width", + percent +"%");
					$('.progress-bar').text(percent +"%");
				}, true);
			}
			return xhr;
		},
		mimeType:"multipart/form-data",
		success: function(response){
			
			var dataList = JSON.parse(response);
			var msgType = dataList.msgType;
			var msg = dataList.msg;
			var FileName = dataList.FileName;

			var filenamelist = '';
			if (msgType == 'success') {
				$("#attachment-files").val(FileName);
				
				if(gComments_id != ''){
					onAddAttachment();
				}
				
				var dateArr = FileName.split("|");
				var index = 1;
				$.each(dateArr, function (key, obj) {
					filenamelist += index+'. '+obj+'  ';
					index++;
				});
				
				if(gComments_id == ''){
					
					$("#attach-file-name").text(filenamelist);
				}else{
					$("#attach-file-name").text('');
				}
				
				$('#attach_form_id').popup('hide');
			} else {
				$("#attach-file-name").text('');
				$("#attachment-files").val('');
			}
		},
		error: function(){
			return false;
		}
	});
}

function onAddAttachment() {
	var filename = $("#attachment-files").val();

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/addAttachment',
		data: 'comments_id='+gComments_id 
			+ '&task_id='+gTask_id 
			+ '&attachment-files='+filename
			+ '&userid='+userid
			+'&project_id='+project_id,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onCommentsAttachment(gTask_id, gTaskGroupId);
				resetForm("Comments_formId");
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onAttachTitleEdit(attachment_id) {
	var attachTitleTxt = $("#geTattach_titleTxt_"+attachment_id).text();
	$("#attach_title_txt_"+attachment_id).val(attachTitleTxt);
	$("#att_icon_"+attachment_id).hide();
	$("#attach_control_"+attachment_id).hide();
	$("#text_muted_att_"+attachment_id).hide();
	$("#geTattach_titleTxt_"+attachment_id).hide();
	$("#attach_box_"+attachment_id).show();
}

function onAttachTitleBoxsh(attachment_id) {
	$("#attach_box_"+attachment_id).hide();
	$("#attach_title_txt_"+attachment_id).val('');
	$("#att_icon_"+attachment_id).show();
	$("#attach_control_"+attachment_id).show();
	$("#text_muted_att_"+attachment_id).show();
	$("#geTattach_titleTxt_"+attachment_id).show();
}

function onAttachTitleSave(task_id, attachment_id) {

	var attachTitleTxt = $("#attach_title_txt_"+attachment_id).val();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/updateAttachTitle',
		data: 'attachment_id='+attachment_id 
			+ '&attach_title='+attachTitleTxt 
			+ '&userid='+userid,
		success: function (response) {			
            var msgType = response.msgType;
            var msg = response.msg;
            if (msgType == "success") {
				onSuccessMsg(msg);
				onAttachTitleBoxsh(attachment_id);
				onCommentsAttachment(task_id, gTaskGroupId);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

//End of Comments Attachment

function onLoadLibraryJS() {
	//owlCarousel
	$('.tasks-board').owlCarousel({
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        loop: false,
		items:3,
        nav: true,
        dots: true,
        mouseDrag: false,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
	});
	
	/*mCustomScrollbar*/
	$(".task-body").mCustomScrollbar({
		theme:"minimal"
	});
	
	/*mouseenter*/
	$(".task-list").mouseenter(function() {
		onSortable(this.id);
	});
	
	$(".owl-stage").mouseenter(function() {
		onSorTableTaskGroup();
	});
}

function onTasklistSortable(tasklistObject) {

	if(jQuery.isEmptyObject(tasklistObject)){
		return;
	}

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/onTasklistSortable',
		data: 'tasklistObject='+JSON.stringify(tasklistObject),
		success: function (response) {
		}
    });
}

function onSortable(id) {
	var tasklistObject = {};
	$("#"+id).sortable({
		placeholder: 'sortable_placeholder',
		start: function( event, ui ) { 
			$(ui.item).addClass("cursor_move");
		},
		stop: function(ev, ui) {
			$(ui.item).removeClass("cursor_move");
			var children = $("#"+id).sortable('refreshPositions').children();
			var i =1;
			$.each(children, function() {
				var data = $(this);
				tasklistObject[i] = data[0].id;
				
				i++;
			});
			onTasklistSortable(tasklistObject);
		}
	});
}

function onSorTableTaskGroup() {

	var TaskGroupObject = {};
	$(".owl-stage").sortable({
		//placeholder: 'taskgroup_placeholder',
		start: function( event, ui ) { 
			//$(ui.item).addClass("cursor_move");
		},
		stop: function(ev, ui) {
			//$(ui.item).removeClass("cursor_move");
			var children = $(".owl-stage").sortable('refreshPositions').children();
			var i =1;
			$.each(children, function(key, obj) {
				var string = obj.firstChild.id;
				var res = string.split("_");
				TaskGroupObject[i] = res[1];
				
				i++;
			});
			onTaskGroupSortable(TaskGroupObject);
		}
	});
}

function onTaskGroupSortable(TaskGroupObject) {

	if(jQuery.isEmptyObject(TaskGroupObject)){
		return;
	}

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/onTaskGroupSortable',
		data: 'TaskGroupObject='+JSON.stringify(TaskGroupObject),
		success: function (response) {
		}
    });
}

function showPerslyError() {
    $('.parsley-error-list').show();
}

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(".tabs-nav li a.active").removeClass("active");
	$("#is_project").addClass("active");	
	
	onProjectInfo();
	
	jQuery('#progress-wrp').hide();
	resetForm("DataEntry_formId");
	
	onInviteStaffPhoto();
	onLoadTaskBoardData();
	onTaskGroup();
	
    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });
	
    $('#addatask-form').click(function () {
        $("#AddaTask_formId").submit();
    });
	
    $('#TaskStatus-form').click(function () {
        $("#TaskStatus_formId").submit();
    });
	
    $('#addComments-form').click(function () {
        $("#Comments_formId").submit();
    });
	
	$('#attach_form_id').popup({
		closebutton: false,
		transition: 'all 0.3s'
	});
	
	$("#invite_staff_search_id").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#invite_staff_list_id tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	
	$("#staff_search_id").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#staff_list_id tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	
    $("#FileName").change(function(e) {
		var filecount = document.getElementById('FileName').files.length;
		var FileName = e.target.files[0].name;

		if(filecount>1){
			$(".filecount").text(TEXT['Total Records']+': '+filecount);
		}else{
			$(".filecount").text(FileName);
		}
		$("#progress-wrp").hide();
		$('.progress-bar').css("width", "0%");
		$('.progress-bar').text("0%");
    });
	
	$("#task_date").datetimepicker({
		format: 'yyyy-mm-dd hh:ii:ss',
		autoclose: true,
		todayBtn: true
	});
	
	$("#status_task_date").datetimepicker({
		format: 'yyyy-mm-dd hh:ii:ss',
		autoclose: true,
		todayBtn: true
	});
	
	onLoadLibraryJS();
	
});
