"use strict";

var $ = jQuery.noConflict();

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

	onLoadEditData();
	
    $("#FileName").change(function() {
		upload_Form();
    });

	$('.toggle-password').on('click', function() {
		$(this).toggleClass('fa-eye-slash');
			let input = $($(this).attr('toggle'));
		if (input.attr('type') == 'password') {
			input.attr('type', 'text');
		}else {
			input.attr('type', 'password');
		}
	});	
}); 

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getProfileData',
		data: 'id='+userid,
		success: function (response) {
			var data = response;
			
			var passtype = $('#password').attr('type');
			if(passtype == 'text'){
				$(".toggle-password").removeClass("fa-eye-slash");
				$(".toggle-password").addClass("fa-eye");
				$('#password').attr('type', 'password');
			}
	
			$("#RecordId").val(data.id);
			$("#name").val(data.name);
			$("#email").val(data.email);
			$("#password").val(data.bactive);
			$("#designation").val(data.designation);
			$("#phone").val(data.phone);
			$("#skype_id").val(data.skype_id);
			$("#facebook_id").val(data.facebook_id);
			$("#address").val(data.address);
			$("#roles_id").val(data.roles_id);
			$("#client_id").val(data.client_id);
			if(data.photo != null){
				$("#file-uploader").html('<img src="'+public_path+'/media/'+data.photo+'">');
				$("#photo").val(data.photo);
			}else{
				$("#file-uploader").html('<img src="'+public_path+'/assets/images/default.png">');
				$("#photo").val('');
			}
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
		url: base_url + '/backend/saveProfileData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function upload_Form() {

	var data = new FormData();
		data.append('FileName', $('#FileName')[0].files[0]);
	var ReaderObj = new FileReader();
	var imgname  =  $('input[type=file]').val();
	var size  =  $('#FileName')[0].files[0].size;

	var ext =  imgname.substr((imgname.lastIndexOf('.') +1));
	if(ext=='jpg' || ext=='jpeg' || ext=='png' || ext=='gif' || ext=='PNG' || ext=='JPG' || ext=='JPEG'){
	 
		if(size<=1000000){
			$.ajax({
				url: base_url + '/backend/FileUpload',
				type: "POST",
				dataType : "json",
				data:  data,
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
						$("#file-uploader").html('<img src="'+public_path+'/media/'+FileName+'">');
						$("#photo").val(FileName);
						$(".errorMgs").hide();
						$(".errorMgs").html('');
						
					} else {
						$("#file-uploader").html('<img src="'+public_path+'/assets/images/default.png">');
						$("#photo").val('');
						$(".errorMgs").show();
						$(".errorMgs").html(msg);
					}
				},
				error: function(){
					return false;
				}
			});
		}else{
			$("#file-uploader").html('<img src="'+public_path+'/assets/images/default.png">');
			$("#photo").val('');
			$(".errorMgs").show();
			$(".errorMgs").html(TEXT['Sorry file size exceeding from 1 Mb']);
		}
	}else{
		$("#file-uploader").html('<img src="'+public_path+'/assets/images/default.png">');
		$("#photo").val('');
		$(".errorMgs").show();
		$(".errorMgs").html(TEXT['Sorry only you can upload jpg, png and gif file type']);
	}
}
