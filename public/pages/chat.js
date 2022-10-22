"use strict";

var $ = jQuery.noConflict();
var start = 0;
var length = 20;
var isPaused = false;
var userid = 0;

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	$(".msg_count").hide();
	
	$('.userSidebarCollapse').on('click', function () {
		$('.chatbot-sidebar, .chatbot-body').toggleClass('active');
	});
	
	onLoadUserList();
	
    $("#chatUserSearch").on("input", function(){
		onLoadUserList();
    });
	
	$("#msgSearch").val('');
	$('#msgSearchCollapse').on('click', function () {
		$("#msgSearch").val('');
		$('#msgSearchBox_collapse').toggleClass('active');
	});
	
	$('#msgRefresh').on('click', function () {
		start = 0;
		$("#msgSearch").val('');
		
		getMessageList();
		
		$('#message_list').animate({
			scrollTop: $('#message_list').prop("scrollHeight")
		}, 500);
	});
	
	$('#older_msg').on('click', function () {
		start = start + length;

		getMessageList();
		
		$('#message_list').animate({
			scrollTop: $('#message_list').prop("scrollHeight")
		}, 500);

	});
	
	$('#newer_msg').on('click', function () {
		start = 0;
		getMessageList();
		
		$('#message_list').animate({
			scrollTop: $('#message_list').prop("scrollHeight")
		}, 500);
	});
	
	$("#message_chatbot").hide();

    $("#msgSearch").on("input", function(){
		getMessageList();
    });
	
    $("#chat_formid").submit(function(e){
		e.preventDefault();
		
		onSaveMessage();
	});	

	$("#chat_mes_file").change(function() {
		chat_mes_file_upload_form();
    });
	
	var t = window.setInterval(function() {
		if(!isPaused) {
			if(userid != 0){
				getMessageList();
				$('#message_list').animate({
					scrollTop: $('#message_list').prop("scrollHeight")
				}, 500);				
			}
		}
	}, 2000);

	$(".chatbot-content").mouseover(function(e){
		e.preventDefault();
		isPaused = true;
	});
	
	$(".chatbot-content").mouseout(function(e){
		e.preventDefault();
		isPaused = false;
	});
	
	$("#older_msg").mouseover(function(e){
		e.preventDefault();
		isPaused = true;
	});
	
	$("#older_msg").mouseout(function(e){
		e.preventDefault();
		isPaused = false;
	});
	
	$("#newer_msg").mouseover(function(e){
		e.preventDefault();
		isPaused = true;
	});
	
	$("#newer_msg").mouseout(function(e){
		e.preventDefault();
		isPaused = false;
	});
	
	$("#type_message").focus(function() {
		onMessageSeen();
	});
});

function onSaveMessage(){
	isPaused = true;
	var user_id = $("#user_id").val();
	var type_message = $.trim($("#type_message").val());
	
	if(user_id == ''){
		onErrorMsg('Please select user.');
		return;
	}
	
	if(type_message == ''){
		return;
	}

	$.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveMessage',
		data: 'chat_mes_text='+type_message
			+'&me_id='+me_id
			+'&user_id='+user_id
			+'&message_id='+$("#message_id").val(),
		success: function (response) {
			
			resetForm("chat_formid");
			
			var msgType = response.msgType;
			var msg = response.msg;
			
			if (msgType == "success") {

				getMessageList();
				isPaused = false;				
			}
		}
	});
}

function chat_mes_file_upload_form() {
	isPaused = true;

	var data = new FormData();
	var fileCount = document.getElementById('chat_mes_file').files.length;

	for (var x = 0; x < fileCount; x++) {
		data.append("FileName[]", document.getElementById('chat_mes_file').files[x]);
	}
	
	var ReaderObj = new FileReader();
	
	$("#file_loader").show();
	
	$.ajax({
		url: base_url + '/backend/attachmentUpload',
		type: "POST",
		dataType : "json",
		data: data,
		contentType: false,
		processData:false,
		enctype: 'multipart/form-data',
		mimeType:"multipart/form-data",
		success: function(response){
			var dataList = response;
			var msgType = dataList.msgType;
			var msg = dataList.msg;
			var FileName = dataList.FileName;

			if (msgType == 'success') {
				onSaveFile(FileName);
			}
		},
		error: function(){
			return false;
		}
	});
}

function onSaveFile(FileName) {
	var user_id = $("#user_id").val();
	start = 0;

	$.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveFile',
		data: 'me_id='+me_id
			+'&user_id='+user_id
			+'&files='+FileName,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;
			if (msgType == "success") {
				getMessageList();
				$("#file_loader").hide();
				isPaused = false;
			}
		}
	});
}

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

function onLoadUserList() {
	$("#user_loader").show();
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getUserList',
		data: 'me_id='+me_id+'&search='+$("#chatUserSearch").val(),
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
					
					if(obj.is_count == 0){
						var is_count = '';
					}else{
						var is_count = '<span id="chat_count_'+obj.id+'"><span class="new_count">'+obj.is_count+' '+TEXT['New']+'</span></span>';
					}
					
					if(obj.is_active == 1){
						var is_active = 'online';
					}else{
						var is_active = 'offline';
					}
					
					if(obj.login_datetime != ''){
						var login_datetime = obj.login_datetime;
						var notDatetime = '';
					}else{
						var login_datetime = '';
						var notDatetime = obj.notDatetime;
					}

					html += '<li class="chat_user_active" id="chat_user_'+obj.id+'" onclick="selectUser('+obj.id+')">'
						+'<div class="chat-avatar">'
							+photo
							+'<div class="online-status '+is_active+'"><i class="fa fa-circle"></i></div>'
						+'</div>'
						+'<div class="chat-user-info '+notDatetime+'">'
							+'<div class="chat-name">'+name+is_count+'</div>'
							+'<div class="chat-preview">'+login_datetime+'</div>'
						+'</div>'
					+'</li>';
				});
			}
			
			$("#chat-users").html(html);
			$("#user_loader").hide();
        }
    });
}

function selectUser(id) {

	$(".chat_user_active").removeClass("active");
	$("#chat_user_"+id).addClass("active");
	$("#user_id").val(id);
	userid = id;
	start = 0;

	getMessageList();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getUserById',
		data: 'id='+id,
		success: function (response) {		
			var data = response;
		
			$('#message_list').animate({
				scrollTop: $('#message_list').prop("scrollHeight")
			}, 500);
		
			if(data.photo != null){
				var photo = '<img src="'+public_path+'/media/'+data.photo+'">';
			}else{
				var photo = '<img src="'+public_path+'/assets/images/default.png">';
			}
			
			if(data.name != null){
				var name = data.name;
			}else{
				var name = '';
			}

			var html = '<div class="contact-profile">'+photo+'</div><div class="contact-name">'+name+'</div>';

			$("#connect_user").html(html);
        }
    });
}

function getMessageList() {
	var user_id = $("#user_id").val();

	$.ajax({
		type : 'POST',
		url: base_url + '/backend/getMessageList',
		data:'me_id='+me_id
			+'&user_id='+user_id
			+'&start='+start
			+'&length='+length
			+'&search='+$("#msgSearch").val(),
		success: function (response) {
			var data = response;

			var html = '';
			if(data.length>0){
				$.each(data, function (key, obj) {
 
					if(obj.chat_mes_text != null){
						var chat_mes_text = obj.chat_mes_text;
					}else{
						var chat_mes_text = '';
					}

					if(obj.chat_mes_file != null){
						var chat_mes_file = obj.chat_mes_file;
					}else{
						var chat_mes_file = '';
					}
					
					if(obj.chat_mes_img != null){
						var chat_mes_img = obj.chat_mes_img;
					}else{
						var chat_mes_img = '';
					}
					
					if(obj.is_me_id == me_id){
						var me = 'me';
						if(obj.is_seen == 0){
							var name_datetime = obj.chat_datetime;
						}else{
							var name_datetime = '<span class="chat-seen">✓✓</span>'+obj.chat_datetime;
						}
						var editIcon = '<li><a title="'+TEXT['Edit']+'" onclick="onEditMessage('+obj.id+');" href="javascript:void(0)"><i class="fa fa-pencil"></i></a></li>';
						var deleteIcon = '<li><a title="'+TEXT['Delete']+'" onclick="onDeleteMessage('+obj.id+');" href="javascript:void(0)"><i class="fa fa-trash-o"></i></a></li>';
						
						if(obj.photo != null){
							var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
						}else{
							var photo = '<img src="'+public_path+'/assets/images/default.png">';
						}
						
					}else{
						var me = '';
						var name_datetime = obj.name+', '+obj.chat_datetime;
						var editIcon = '';
						var deleteIcon = '';
						
						if(obj.photo != null){
							var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
						}else{
							var photo = '<img src="'+public_path+'/assets/images/default.png">';
						}
					}
					
					if(chat_mes_file !=''){
						html += '<li><div class="message-bubble '+me+'">'
							+'<div class="message-bubble-inner clearfix">'
								+'<div class="message-avatar">'
									+photo
								+'</div>'
								+'<div class="message-name-datetime">'
									+name_datetime
								+'</div>'
								+'<div class="message-text">'
									+ '<a download href="'+public_path+'/media/'+chat_mes_file+'">'+chat_mes_file+'</a>'
									+'<ul class="chat-control">'
										+deleteIcon
										+'<li><a title="'+TEXT['Download']+'" download href="'+public_path+'/media/'+chat_mes_file+'"><i class="fa fa-download"></i></a></li>'
									+'</ul>'
								+'</div>'
							+'</div>'
						+'</div></li>';
						
					} else if (chat_mes_img !='') {
						
						html += '<li><div class="message-bubble '+me+'">'
							+'<div class="message-bubble-inner clearfix">'
								+'<div class="message-avatar">'
									+photo
								+'</div>'
								+'<div class="message-name-datetime">'
									+name_datetime
								+'</div>'
								+'<div class="message-text m-image">'
									+'<div class="image">'
									+'<a href="'+public_path+'/media/'+chat_mes_img+'" data-lity><img src="'+public_path+'/media/'+chat_mes_img+'"></a>'
									+'</div>'
									+'<ul class="chat-control">'
										+deleteIcon
										+'<li><a title="'+TEXT['Download']+'" download href="'+public_path+'/media/'+chat_mes_img+'"><i class="fa fa-download"></i></a></li>'
									+'</ul>'
								+'</div>'
							+'</div>'
						+'</div></li>';
						
					}else{
					
						html += '<li><div class="message-bubble '+me+'">'
							+'<div class="message-bubble-inner clearfix">'
								+'<div class="message-avatar">'
									+photo
								+'</div>'
								+'<div class="message-name-datetime">'
									+name_datetime
								+'</div>'
								+'<div class="message-text">'
									+ chat_mes_text
									+'<ul class="chat-control">'
										+deleteIcon
										//+editIcon
									+'</ul>'
								+'</div>'
							+'</div>'
						+'</div></li>';
					}
				});
			}

			$("#message_list").html(html);
			
			$("#welcome_id").hide();

			if(data.length == length){
				$("#older_msg").show();
			}else{
				$("#older_msg").hide();
			}

			if(start == 0){
				$("#newer_msg").hide();
			}else{
				$("#newer_msg").show();
			}
			
			$("#message_chatbot").show();			
		}
	});
}

function onDeleteMessage(id) {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteMessageById',
		data: 'id='+id,
		success: function (response) {		
            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				getMessageList();
				$("#message_id").val('');
				$("#type_message").val('');
            }
        }
    });
}

function onEditMessage(id) {
	$("#type_message").val('');
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/editMessageById',
		data: 'id='+id,
		success: function (response) {	
            var data = response;
			if(data.chat_mes_text != null){
				var chat_mes_text = data.chat_mes_text;
			}else{
				var chat_mes_text = '';
			}
			
			$("#message_id").val(id);
			$("#type_message").val(chat_mes_text);
        }
    });
}

function onMessageSeen() {
	var user_id = $("#user_id").val();
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/MessageSeenSave',
		data: 'me_id='+me_id +'&user_id='+user_id,
		success: function (response) {
			$("#chat_count_"+user_id).html('');
        }
    });
}

