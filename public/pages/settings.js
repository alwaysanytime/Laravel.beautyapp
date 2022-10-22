"use strict";

var $ = jQuery.noConflict();

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	onTimezone();
	
    $('#global-setting-form').click(function () {
        $("#DataEntry_formId").submit();
    });

    $('#recaptcha-submit-form').click(function () {
        $("#GoogleRecaptcha_formId").submit();
    });
	
    $('#mailsetting-submit-form').click(function () {
        $("#MailSetting_formId").submit();
    });
	
    $('#stripe-submit-form').click(function () {
        $("#StripeSettings_formId").submit();
    });
	
    $('#pcode-submit-form').click(function () {
        $("#PurchaseCode_formId").submit();
    });
	
    $("#load_favicon").change(function() {
		favicon_upload_Form();
    });	
	
    $("#load_logo").change(function() {
		logo_upload_Form();
    });	
	
	$('#color-picker').colorpicker({
		format: 'hex' //format - hex | rgb | rgba.
	});

});

function onListPanel() {
    $('#list-panel').show();
    $('#form-panel').hide();
}

function onEditPanel() {
    $('#list-panel').hide();
    $('#form-panel').show();
}

function onClickSetting(id) {
	if(id == 1){
		$("#GoogleRecaptchaSetting").hide();
		$("#MailSetting").hide();
		$("#StripeSettings").hide();
		$("#PurchaseCodeId").hide();
		$("#GlobalSetting").show();
		$(".tabs-nav li a.active").removeClass("active");
		$("#tabId-"+id).addClass("active");
	} else if(id == 2){
		$("#GlobalSetting").hide();
		$("#MailSetting").hide();
		$("#StripeSettings").hide();
		$("#PurchaseCodeId").hide();
		$("#GoogleRecaptchaSetting").show();
		$(".tabs-nav li a.active").removeClass("active");
		$("#tabId-"+id).addClass("active");
	} else if(id == 3){
		$("#GlobalSetting").hide();
		$("#GoogleRecaptchaSetting").hide();
		$("#StripeSettings").hide();
		$("#PurchaseCodeId").hide();
		$("#MailSetting").show();
		$(".tabs-nav li a.active").removeClass("active");
		$("#tabId-"+id).addClass("active");
	} else if(id == 4){
		$("#GlobalSetting").hide();
		$("#GoogleRecaptchaSetting").hide();
		$("#MailSetting").hide();
		$("#PurchaseCodeId").hide();
		$("#StripeSettings").show();
		$(".tabs-nav li a.active").removeClass("active");
		$("#tabId-"+id).addClass("active");
	} else if(id == 5){
		$("#GlobalSetting").hide();
		$("#GoogleRecaptchaSetting").hide();
		$("#MailSetting").hide();
		$("#StripeSettings").hide();
		$("#PurchaseCodeId").show();
		$(".tabs-nav li a.active").removeClass("active");
		$("#tabId-"+id).addClass("active");
	}
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
			
			onLoadGlobalSettingData();
        }
    });
}

function onLoadGlobalSettingData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getGlobalSettingData',
		success: function (response) {			
			var data = response;

			$.each(data, function (key, obj) {
				
				if(obj.theme_color != null){
					$("#theme_color").colorpicker('setValue', obj.theme_color);
				}else{
					$("#theme_color").colorpicker('setValue', '#45a6af');
				}
				
				$("#timezone_id").val(obj.timezone_id).trigger("chosen:updated");
				
				if(obj.favicon != null){
					$("#favicon").val(obj.favicon);
					$("#favicon_show").html('<img src="'+public_path+'/media/'+obj.favicon+'">');
				}else{
					$("#favicon").val('');
					$("#favicon_show").html('');
				}
				
				if(obj.logo != null){
					$("#logo").val(obj.logo);
					$("#logo_show").html('<img src="'+public_path+'/media/'+obj.logo+'">');
				}else{
					$("#logo").val('');
					$("#logo_show").html('');
				}
				
				$("#RecordId").val(obj.id);
				
				if(obj.company_name != null){
					$("#company_name").val(obj.company_name);
				}else{
					$("#company_name").val('');
				}
				
				if(obj.company_title != null){
					$("#company_title").val(obj.company_title);
				}else{
					$("#company_title").val('');
				}
				
				if(obj.siteurl != null){
					$("#siteurl").val(obj.siteurl);
				}else{
					$("#siteurl").val('');
				}
			});
			
			$("#tw-loader").hide();
			
			getMailSettingData();
        }
    });
}

function getMailSettingData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMailSettingData',
		success: function (response) {			
			var data = response;

			var MailSetting = '';
			var index = 0;
			$.each(data, function (key, obj) {
				if(index == 0){
					MailSetting += '<div class="card">'
						+'<div class="card-header" id="headingOne_'+obj.id+'">'
							+'<h3 data-toggle="collapse" data-target="#collapseOne_'+obj.id+'" aria-expanded="true" aria-controls="collapseOne_'+obj.id+'">'+obj.subject_value+'</h3>'
						+'</div>'
						+'<div id="collapseOne_'+obj.id+'" class="collapse show" aria-labelledby="headingOne_'+obj.id+'" data-parent="#accordion">'
						  +'<div class="card-body">'
							+'<div class="form-group">'
								+'<label><span class="red">*</span> '+TEXT['Subject']+'</label>'
								+'<input type="text" name="mailsubject['+obj.id+']" value="'+obj.subject_value+'" class="form-control">'
							+'</div>'
							+'<div class="form-group">'
								+'<label><span class="red">*</span> '+TEXT['Body']+'</label>'
								+'<textarea name="mailbody['+obj.id+']" class="form-control" rows="2">'+obj.body_value+'</textarea>'
							+'</div>'
						  +'</div>'
						+'</div>'
					+'</div>';
				}else{
					
					MailSetting += '<div class="card">'
						+'<div class="card-header" id="heading2_'+obj.id+'">'
							+'<h3 class="collapsed" data-toggle="collapse" data-target="#collapse2_'+obj.id+'" aria-expanded="false" aria-controls="collapse2_'+obj.id+'">'+obj.subject_value+'</h3>'
						+'</div>'
						+'<div id="collapse2_'+obj.id+'" class="collapse" aria-labelledby="heading2_'+obj.id+'" data-parent="#accordion">'
						  +'<div class="card-body">'
							+'<div class="form-group">'
								+'<label><span class="red">*</span> '+TEXT['Subject']+'</label>'
								+'<input type="text" name="mailsubject['+obj.id+']" value="'+obj.subject_value+'" class="form-control">'
							+'</div>'
							+'<div class="form-group">'
								+'<label><span class="red">*</span> '+TEXT['Body']+'</label>'
								+'<textarea name="mailbody['+obj.id+']" class="form-control" rows="2">'+obj.body_value+'</textarea>'
							+'</div>'
						  +'</div>'
						+'</div>'
					  +'</div>';
				}
				
				index++
			});

			$("#MailSetting_id").html(MailSetting);
			
			onPcodeData();
        }
    });
}

function onPcodeData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getPcodeData',
		success: function (response) {			
			var data = response;
			if(data.length>0){
				$.each(data, function (key, obj) {

					if(obj.id != null){
						$("#pcode_id").val(obj.id);
					}else{
						$("#pcode_id").val('');
					}
					
					if(obj.pcode != null){
						$("#pcode").val(obj.pcode);
					}else{
						$("#pcode").val('');
					}
				});
				
				$("#deregister_id").show();
				$("#registered_id").hide();
			}else{
				$("#pcode_id").val('');
				$("#pcode").val('');
				$("#deregister_id").hide();
				$("#registered_id").show();
			}
        }
    });
}

function onPcodeDelete() {
	var msg = TEXT["Do you really want to deregister the theme"];
	onCustomModal(msg, "onConfirmWhenDelete");	
}

function onConfirmWhenDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deletePcode',
		data: 'id='+$("#pcode_id").val(),
		success: function (response) {
			
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				$("#pcode_id").val('');
				$("#pcode").val('');
				$("#deregister_id").hide();
				$("#registered_id").show();
				onPcodeData();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
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
                onGlobalSettingUpdate();
                return false;
            }
        }
    }
});

function onGlobalSettingUpdate() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/globalSettingUpdate',
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

jQuery('#GoogleRecaptcha_formId').parsley({
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
                onGoogleRecaptchaUpdate();
                return false;
            }
        }
    }
});

function onGoogleRecaptchaUpdate() {
	var recaptcha = $('#recaptcha').is(':checked');
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/GoogleRecaptchaUpdate',
		data: $('#GoogleRecaptcha_formId').serialize() + '&recaptcha='+recaptcha,
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

jQuery('#MailSetting_formId').parsley({
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
                onMailSettingUpdate();
                return false;
            }
        }
    }
});

function onMailSettingUpdate() {
	var isnotification = $('#isnotification').is(':checked');

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/MailSettingUpdate',
		data: $('#MailSetting_formId').serialize() + '&isnotification='+isnotification,
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

jQuery('#StripeSettings_formId').parsley({
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
                onStripeUpdate();
                return false;
            }
        }
    }
});

function onStripeUpdate() {
	var isenable = $('#isenable').is(':checked');
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/StripeUpdate',
		data: $('#StripeSettings_formId').serialize() + '&isenable='+isenable,
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;
			
            if (msgType == "success") {
				$("#stripe_id").val(response.stripe_id);
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

jQuery('#PurchaseCode_formId').parsley({
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
                onPurchaseCodeUpdate();
                return false;
            }
        }
    }
});

function onPurchaseCodeUpdate() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/PurchaseCodeUpdate',
		data: $('#PurchaseCode_formId').serialize(),
		success: function (response) {
            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				onPcodeData();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function favicon_upload_Form() {

	var data = new FormData();
		data.append('FileName', $('#load_favicon')[0].files[0]);
	var ReaderObj = new FileReader();
	var imgname  =  $('#load_favicon').val();
	var size  =  $('#load_favicon')[0].files[0].size;

	var ext =  imgname.substr((imgname.lastIndexOf('.') +1));
	if(ext=='jpg' || ext=='jpeg' || ext=='png' || ext=='gif' || ext=='PNG' || ext=='JPG' || ext=='JPEG' || ext=='ico'){
	 
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
						$("#favicon_show").html('<img src="'+public_path+'/media/'+FileName+'">');
						$("#favicon").val(FileName);
						$("#favicon_errorMgs").hide();
						$("#favicon_errorMgs").html('');
					} else {
						$("#favicon_show").html('');
						$("#favicon").val('');
						$("#favicon_errorMgs").show();
						$("#favicon_errorMgs").html(msg);
					}
				},
				error: function(){
					return false;
				}				
			});
		}else{
			$("#favicon_show").html('');
			$("#favicon").val('');
			$("#favicon_errorMgs").show();
			$("#favicon_errorMgs").html(TEXT['Sorry file size exceeding from 1 Mb']);
		}
	}else{
		$("#favicon_show").html('');
		$("#favicon").val('');
		$("#favicon_errorMgs").show();
		$("#favicon_errorMgs").html(TEXT['Sorry only you can upload jpg, png and gif file type']);
	}
}

function logo_upload_Form() {

	var data = new FormData();
		data.append('FileName', $('#load_logo')[0].files[0]);
	var ReaderObj = new FileReader();
	var imgname  =  $('#load_logo').val();
	var size  =  $('#load_logo')[0].files[0].size;

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
						$("#logo_show").html('<img src="'+public_path+'/media/'+FileName+'">');
						$("#logo").val(FileName);
						$("#logo_errorMgs").hide();
						$("#logo_errorMgs").html('');
					} else {
						$("#logo_show").html('');
						$("#logo").val('');
						$("#logo_errorMgs").show();
						$("#logo_errorMgs").html(msg);
					}
				},
				error: function(){
					return false;
				}				
			});
		}else{
			$("#logo_show").html('');
			$("#logo").val('');
			$("#logo_errorMgs").show();
			$("#logo_errorMgs").html(TEXT['Sorry file size exceeding from 1 Mb']);
		}
	}else{
		$("#logo_show").html('');
		$("#logo").val('');
		$("#logo_errorMgs").show();
		$("#logo_errorMgs").html(TEXT['Sorry only you can upload jpg, png and gif file type']);
	}
}
