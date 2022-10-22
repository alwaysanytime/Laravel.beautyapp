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
/*		onActiveStatus();
		onCountry();
	*/
    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });

	onLoadClientData();

    $("#search_txt").on("input", function(){
		onLoadClientData();
    });

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
	var passtype = $('#password').attr('type');
	if(passtype == 'text'){
		$(".toggle-password").removeClass("fa-eye-slash");
		$(".toggle-password").addClass("fa-eye");
		$('#password').attr('type', 'password');
	}
    resetForm("DataEntry_formId");
	RecordId = '';
	$("#file-uploader").html('<img src="'+public_path+'/assets/images/default.png">');
	$("#photo").val('');
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
    $('.search').hide();
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
	$('.search').hide();
}

function onCountry() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getCountryList',
		success: function (response) {
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.country_name + '</option>';
			});

			$("#country_id").html(html);
			$("#country_id").chosen();
			$("#country_id").trigger("chosen:updated");
        }
    });
}

function onLoadClientData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getClientData',
		data: 'search='+$("#search_txt").val(),
		success: function (response) {
			var data = response;
			var html = '';
			if(data.length>0){
				$.each(data, function (key, obj) {
/*
					if(obj.photo != null){
						var photo = '<img src="'+public_path+'/media/'+obj.photo+'">';
					}else{
						var photo = '<img src="'+public_path+'/assets/images/default.png">';
					}
*/
					if(obj.firstname != null){
						var firstname = obj.firstname;
					}else{
						var firstname = '';
					}
					if(obj.lastname != null){
						var lastname = obj.lastname;
					}else{
						var lastname = '';
					}
					if(obj.address1 != null){
						var address1 = obj.address1;
					}else{
						var city = '';
					}
					if(obj.city != null){
						var city = obj.city;
					}else{
						var city = '';
					}
					if(obj.mobile != null){
						var mobile = obj.mobile;
					}else{
						var mobile = '';
					}

					html += '<div class="col-md-4 col-lg-3 col-xl-3 mb-10">'
							+'<div class="tw_box">'
								+'<div class="tw_info">'
									+'<h2><a>'+firstname+' '+lastname+'</a></h2>'
									+'<p>'+address1+'</p>'
									+'<p>'+city+'</p>'
									+'<p>'+mobile+'</p>'
								+'</div>'
								+'<div class="tw_control">'
									+'<ul>'
										+'<li><a onclick="onEditData('+obj.id+');" href="javascript:void(0);" title="'+TEXT['Edit']+'"><i class="fa fa-pencil"></i></a></li>'
										+'<li><a href="'+base_url+'/backend/contract/?id='+obj.id+'" title="Vereinbarung"><i class="fa fa-handshake-o"></i></a></li>'
										+'<li><a href="'+base_url+'/backend/fileuploadtest/?id='+obj.id+'" title="Fotos hochladen"><i class="fa fa-camera"></i></a></li>'
								+'</div>'
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

function onConfirmWhenAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveClientData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				onLoadClientData();
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
		url: base_url + '/backend/getClientById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;

			var passtype = $('#password').attr('type');
			if(passtype == 'text'){
				$(".toggle-password").removeClass("fa-eye-slash");
				$(".toggle-password").addClass("fa-eye");
				$('#password').attr('type', 'password');
			}

			$("#RecordId").val(data.id);
			$("#firstname").val(data.firstname);
			$("#lastname").val(data.lastname);
			$("#email").val(data.email);
			$("#mobile").val(data.mobile);
			$("#phone").val(data.phone);
			$("#address1").val(data.address1);
			$("#zipcode").val(data.zipcode);
			$("#city").val(data.city);

			if(data.photo != null){
				$("#file-uploader").html('<img src="'+public_path+'/media/'+data.photo+'">');
				$("#photo").val(data.photo);
			}else{
				$("#file-uploader").html('<img src="'+public_path+'/assets/images/default.png">');
				$("#photo").val('');
			}

			onEditPanel();
        }
    });
}

function onViewData(id) {
	RecordId = id;
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getClientById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;

			$("#profile_name").text(data.name);
			$("#profile_desig").text(data.country_name);
			if(data.photo != null){
				var photo = public_path+"/media/"+data.photo;
				$("#profile_head").css("background-image", "url(" + photo + ")");
				$("#profile_image").html('<img src="'+public_path+'/media/'+data.photo+'">');
			}else{
				$("#profile_head").css("background-image", "url("+public_path+"/assets/images/default.png)");
				$("#profile_image").html('<img src="'+public_path+'/assets/images/default.png">');
			}

			var profile_info = '';
			var city_state_zip_code = '';
			if(data.email != null){
				profile_info += '<li><span class="con-icon"><i class="fa fa-envelope"></i></span><div class="con-desc"><a href="mailto:'+data.email+'">'+data.email+'</a></div></li>';
			}else{
				profile_info += '';
			}

			if(data.phone != null){
				profile_info += '<li><span class="con-icon"><i class="fa fa-phone"></i></span><div class="con-desc">'+data.phone+'</div></li>';
			}else{
				profile_info += '';
			}

			if(data.mobile != null){
				profile_info += '<li><span class="con-icon"><i class="fa fa-mobile"></i></span><div class="con-desc">'+data.mobile+'</div></li>';
			}else{
				profile_info += '';
			}

			if(data.city != null){
				city_state_zip_code += '<strong>City: </strong>'+data.city+'<br>';
			}else{
				city_state_zip_code += '';
			}

			if(data.zipcode != null){
				city_state_zip_code += '<strong>Zip Code: </strong>'+data.zipcode;
			}else{
				city_state_zip_code += '';
			}

			if(city_state_zip_code !=''){
				profile_info += '<li><span class="con-icon"><i class="fa fa-university"></i></span><div class="con-desc">'+city_state_zip_code+'</div></li>';
			}else{
				profile_info += '';
			}

			if(data.address1 != null){
				profile_info += '<li><span class="con-icon"><i class="fa fa-map-marker"></i></span><div class="con-desc">'+data.address1+'</div></li>';
			}else{
				profile_info += '';
			}

			$("#profile_info").html(profile_info);
			$('#View_Id').modal('show');
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
            }else {
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

