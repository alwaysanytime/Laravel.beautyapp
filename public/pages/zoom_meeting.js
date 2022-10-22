"use strict";

var $ = jQuery.noConflict();
var MeetingId = '';

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	
	
    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });

	$("#staff_client_search_id").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#staff_client_list_id tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	
	$("#meeting_date").datetimepicker({
		format: 'yyyy-mm-ddThh:ii:ss',
		autoclose: true,
		todayBtn: true
	});

	onTimezone();
	
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
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	MeetingId = '';
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {

    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onTimezone() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTimezoneList',
		success: function (response) {
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.timezone_id + '">' + obj.timezone + '</option>';
			});
			
			$("#timezone_id").html(html);
			$("#timezone_id").chosen();
			$("#timezone_id").trigger("chosen:updated");
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
		url: base_url + '/backend/CreateMeeting',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			
			if (msgType == "success") {
				onSuccessMsg(msg);
				$("#Meeting_TableId").dataTable().fnDraw();
				onListPanel();
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function getMeetingDetails() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMeetingDetails',
		data: 'MeetingId=' + MeetingId,
		success: function (response) {
			var datalist = response;
			$("#meeting_topic").val(datalist.topic);
			$('#meeting_date').val(datalist.start_time).datetimepicker('update');
			$("#meeting_duration").val(datalist.duration);
			$("#timezone_id").val(datalist.timezone).trigger("chosen:updated");
			
            if (datalist.host_video == 1) {
                document.getElementById("host_video").checked = true;
            } else {
                document.getElementById("host_video").checked = false;
            }
			
            if (datalist.participant_video == 1) {
                document.getElementById("participant_video").checked = true;
            } else {
                document.getElementById("participant_video").checked = false;
            }
			
            if (datalist.join_before_host == 1) {
                document.getElementById("enable_join_before_host").checked = true;
            } else {
                document.getElementById("enable_join_before_host").checked = false;
            }
			
            if (datalist.mute_upon_entry == 1) {
                document.getElementById("mute_participants_upon_entry").checked = true;
            } else {
                document.getElementById("mute_participants_upon_entry").checked = false;
            }
			
			onEditPanel();
        }
    });
}

function onDeleteUpcomingMeeting() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteMeeting',
		data: 'MeetingId='+MeetingId,
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onSuccessMsg(msg);
				$("#Meeting_TableId").dataTable().fnDraw();
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function getStaffClientList() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getStaffClientList',
		data: 'meeting_id=' + MeetingId,
		success: function (response) {			
			var datalist = response;
			var html = '';
				html = '<option value="">' + TEXT['Select staff/client']+ '</option>';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.name + '</option>';
			});
			
			$("#StaffClient_id").html(html);
			$("#StaffClient_id").chosen();
			$("#StaffClient_id").trigger("chosen:updated");
        }
    });
}

function getMeetingInvitation(id) {
	
	MeetingId = id;

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMeetingDetails',
		data: 'MeetingId=' + id,
		success: function (response) {
			
			var CopyInvitation = '';
			CopyInvitation += '<p class="mb-0"><strong>'+TEXT['Meeting Topic']+'</strong>: '+response.topic+'</p>';
			CopyInvitation += '<p class="mb-10"><strong>'+TEXT['Time']+'</strong>: '+response.InvitationTime+' '+response.timezone+'</p>';
			CopyInvitation += '<p class="mb-10"><strong>'+TEXT['Join Zoom Meeting']+'</strong>: '+response.join_url+'</p>';
			CopyInvitation += '<p class="mb-0"><strong>'+TEXT['Meeting ID']+'</strong>: '+response.MeetingId+'</p>';
			CopyInvitation += '<p><strong>'+TEXT['Passcode']+'</strong>: '+response.password+'</p>';
			
			$("#CopyInvitation").html(CopyInvitation);
			
			getStaffClientList();
			onMeetingInvitationStaff();
			
			$('#Invitation_Meeting_Topic').val(response.topic);
			$('#Invitation_Time').val(response.InvitationTime);
			$('#Invitation_Timezone').val(response.timezone);
			$('#Invitation_join_url').val(response.join_url);
			$('#Invitation_password').val(response.password);
			
			$('#Meeting_Invitation_Id').modal('show');
        }
    });
}

function onMeetingInvitationStaff() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMeetingInvitationStaff',
		data: 'meeting_id=' + MeetingId,
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
						+'<td class="text-center"><a onclick="onMeetingInvitationDelete('+obj.id+');" href="javascript:void(0)" title="'+TEXT['Delete']+'" class="delete_icon"><i class="fa fa-trash-o"></i></a></td>'
					+'</tr>';
				});
			}else{
				html = '<tr><td colspan="3"><div class="alert alert-warning" role="alert">'+TEXT['No data available']+'</div></td></tr>';
			}
			
			$("#staff_client_list_id").html(html);
        }
    });
}

function onAddMeetingInvitation() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/insertMeetingInvitationData',
		data: $('#MeetingInvitation_formId').serialize()+ '&meeting_id=' + MeetingId,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;
			
			if (msgType == "success") {
				getStaffClientList();
				onMeetingInvitationStaff();
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function onMeetingInvitationDelete(meeting_invitation_id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteMeetingInvitation',
		data: 'meeting_invitation_id=' + meeting_invitation_id,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				getStaffClientList();
				onMeetingInvitationStaff();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

