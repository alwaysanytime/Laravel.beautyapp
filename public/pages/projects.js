"use strict";

var RecordId = '';
var $ = jQuery.noConflict();

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
    onListPanel();
    resetForm("DataEntry_formId");
	onClient();
	onStatus();

    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });

	onLoadProjectsData();
	
    $("#search_txt").on("input", function(){
		onLoadProjectsData();
    });
	
	$("#staff_search_id").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#staff_list_id tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	
	$("#start_date").datetimepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		todayBtn: true,
		minView: 2
	});
	
	$("#end_date").datetimepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		todayBtn: true,
		minView: 2
	});
	
}); 

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

function onListPanel() {
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
	$('.search').show();
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	RecordId = '';
	$("#photo").val('');
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
    $('.search').hide();
	$("#status_id").val(1).trigger("chosen:updated");
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
	$('.search').hide();
}

function onClient() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getClientList',
		success: function (response) {		
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.name + '</option>';
			});
			
			$("#client_id").html(html);
			$("#client_id").chosen();
			$("#client_id").trigger("chosen:updated");
        }
    });
}

function onStatus() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getStatusList',
		success: function (response) {
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.status_name + '</option>';
			});
			
			$("#status_id").html(html);
			$("#status_id").chosen();
			$("#status_id").trigger("chosen:updated");
        }
    });
}

function onStaff(id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getStaffList',
		data: 'project_id='+id,
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
			
			$("#staff_id").html(html);
			$("#staff_id").chosen();
			$("#staff_id").trigger("chosen:updated");
        }
    });
}

function onLoadProjectsData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getProjectData',
		data: 'search='+$("#search_txt").val() 
			+'&role_id='+role_id
			+'&userid='+userid,
		success: function (response) {
			var data = response;
			var html = '';
			if(data.length>0){
				$.each(data, function (key, obj) {

					if(obj.project_name != null){
						var project_name = obj.project_name;
					}else{
						var project_name = '';
					}
					
					if(obj.status_name != null){
						var status_name = obj.status_name;
					}else{
						var status_name = '';
					}
					
					html += '<div class="col-lg-4 mb-30">'
							+'<div class="project">'
								+'<ul class="project-action">'
									+'<li class="edit-project"><a href="'+base_url + '/backend/task-board/'+obj.id+'" title="'+TEXT['Go To Task Board']+'"><i class="fa fa-paper-plane-o"></i></a></li>'
									+'<li class="edit-project"><a onclick="onViewData('+obj.id+');" href="javascript:void(0);" title="'+TEXT['View']+'"><i class="fa fa-eye"></i></a></li>'
									+'<li class="edit-project"><a onclick="onEditData('+obj.id+');" href="javascript:void(0);" title="'+TEXT['Edit']+'"><i class="fa fa-pencil"></i></a></li>'
									+'<li class="delete-project"><a onclick="onDelete('+obj.id+');" href="javascript:void(0)" title="'+TEXT['Delete']+'"><i class="fa fa-trash-o"></i></a></li>'
								+'</ul>'
								+'<p class="project-title mb-10"><a href="'+base_url + '/backend/task-board/'+obj.id+'">'+project_name+'</a></p>'
								+'<ul class="invited-users">'
									+'<li class="in-user-plus"><a onclick="onInviteData('+obj.id+');" href="javascript:void(0);" title="'+TEXT['Invite to project']+'"><i class="fa fa-user-plus"></i></a></li>'
									+ obj.Photo
								+'</ul>'
								+ status_name
							+'</div>'
						+'</div>';	
				});
			}else{
				html = '<div class="col-lg-12"><div class="alert alert-warning" role="alert">'+TEXT['No data available']+'</div></div>';
			}
			
			$("#tw-loader").hide();
			$(".datalist").html(html);
        }
    });
}

function showPerslyError() {
    $('.parsley-error-list').show();
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
                onConfirmWhenAddEdit();
                return false;
            }
        }
    }
});

function onConfirmWhenAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveProjectData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onLoadProjectsData();
				onSuccessMsg(msg);
                onListPanel();
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onEditData(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to edit this record"];
	onCustomModal(msg, "onLoadEditData");	
}

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getProjectById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;
			$("#RecordId").val(data.id);
			$("#project_name").val(data.project_name);
			$("#description").val(data.description);
			$('#start_date').val(data.start_date).datetimepicker('update');
			$('#end_date').val(data.end_date).datetimepicker('update');
			$("#budget").val(data.budget);
			$("#client_id").val(data.client_id).trigger("chosen:updated");
			$("#status_id").val(data.status_id).trigger("chosen:updated");

			onEditPanel();
        }
    });
}

function onViewData(id) {
	RecordId = id;

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getProjectById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;
			var project_info = '';
			
			project_info += '<tr><th style="width:20%;">'+TEXT['Milestones']+'</th><td style="width:80%;"><a href="'+base_url + '/backend/milestones/'+data.id+'" class="btn green-btn" style="padding:5px 15px;"><i class="fa fa-paper-plane-o"></i> '+TEXT['View']+'</a></td></tr>';

			if(data.project_name != null){
				project_info += '<tr><th style="width:20%;">'+TEXT['Project Name']+'</th><td style="width:80%;">'+data.project_name+'</td></tr>';
			}else{
				project_info += '';
			}
			
			if(data.start_date != null){
				project_info += '<tr><th>'+TEXT['Start Date']+'</th><td>'+data.start_date+'</td></tr>';
			}else{
				project_info += '';
			}
			
			if(data.end_date != null){
				project_info += '<tr><th>'+TEXT['End Date']+'</th><td>'+data.end_date+'</td></tr>';
			}else{
				project_info += '';
			}
			
			if(data.client_name != null){
				project_info += '<tr><th>'+TEXT['Client']+'</th><td>'+data.client_name+'</td></tr>';
			}else{
				project_info += '';
			}
			
			if(data.status_name != null){
				project_info += '<tr><th>'+TEXT['Status']+'</th><td>'+data.status_name+'</td></tr>';
			}else{
				project_info += '';
			}
			
			if(data.budget != null){
				project_info += '<tr><th>'+TEXT['Budget']+' ('+TEXT['Currency']+')</th><td>'+data.budget_number+'</td></tr>';
			}else{
				project_info += '';
			}
			
			if(data.description != null){
				project_info += '<tr><th>'+TEXT['Description']+'</th><td>'+data.description+'</td></tr>';
			}else{
				project_info += '';
			}
			
			$("#project_info").html(project_info);			
			$('#View_Id').modal('show');
        }
    });
}

function onDelete(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmWhenDelete");	
}

function onConfirmWhenDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteProject',
		data: 'id='+RecordId+'&userid=' + userid,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onLoadProjectsData();
				onSuccessMsg(msg);
				onListPanel();
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInviteData(id) {
	$('#project_id').val(id);
	onStaff(id);

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getInvitedStaff',
		data: 'id='+id,
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
						var active_id = '<td class="text-center"><a onclick="onInviteActive('+obj.project_staff_id+','+obj.project_id+',0);" href="javascript:void(0)" title="'+TEXT['Active']+'" class="active_icon"><i class="fa fa-check"></i></a></td>';
					}else{
						var active_id = '<td class="text-center"><a onclick="onInviteActive('+obj.project_staff_id+','+obj.project_id+',1);" href="javascript:void(0)" title="'+TEXT['Inactive']+'" class="inactive_icon"><i class="fa fa-times"></i></a></td>';
					}
					
					html += '<tr>'
						+'<td><span class="list-photo">'+photo+'</span></td>'
						+'<td>'+name+' - '+role_name+'</td>'
						+active_id
						+'<td class="text-center"><a onclick="onInviteDelete('+obj.project_staff_id+','+obj.project_id+');" href="javascript:void(0)" title="'+TEXT['Delete']+'" class="delete_icon"><i class="fa fa-trash-o"></i></a></td>'
					+'</tr>';
				});
			}else{
				html = '<tr><td colspan="4"><div class="alert alert-warning" role="alert">'+TEXT['No data available']+'</div></td></tr>';
			}
			
			$("#staff_list_id").html(html);
        }
    });
	
	$('#Invite_Id').modal('show');
}

function onInviteAdd() {
	var projectid = $('#project_id').val();
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveInviteData',
		data: $('#Invite_formId').serialize(),
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteData(projectid);
				onLoadProjectsData();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInviteActive(id, project_id, bActive) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/InviteActiveInactive',
		data: 'id='+id+ '&bActive='+bActive + '&userid=' + userid,
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteData(project_id);
				onLoadProjectsData();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInviteDelete(id, project_id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteInviteProject',
		data: 'id='+id,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onInviteData(project_id);
				onLoadProjectsData();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}
