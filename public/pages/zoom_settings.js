"use strict";

var $ = jQuery.noConflict();

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	
	
	$(".tabs-nav li a.active").removeClass("active");
	$("#zoom-meeting").addClass("active");	
	$("#tabId-4").addClass("active");
	
    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });
	
	$("#tw-loader").hide();
	
}); 


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
		url: base_url + '/backend/SaveZoomSettings',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			
			if (msgType == "success") {
				$("#zoomSettingId").val(response.id);
				onSuccessMsg(msg);
			} else {
				$("#zoomSettingId").val('');
				onErrorMsg(msg);
			}
		}
	});
}
