"use strict";

var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    resetForm("DataEntry_formId");

	onCountry();

	$("#designation").prop('required', true);
	$("#StaffClient").val(3);

    $("#DataEntry_formId").submit(function(e){
		e.preventDefault();

		$.ajax({
			type : 'POST',
			url: base_url + '/StaffClientRegister',
			data: $('#DataEntry_formId').serialize(),
			success: function (response) {
				alert("hii");
				var msgType = response.msgType;
				var msg = response.msg;

				if (msgType == "success") {
					if(isReCaptcha == 1){
						grecaptcha.reset();
					}
					resetForm("DataEntry_formId");
					$("#msg").html('<div class="alert alert-success" role="alert">'+msg+'</div>');
					$("#msg").show();
				} else {

					var htmlMsg = '';
					$.each(msg, function (key, obj) {
						htmlMsg += '<li>'+obj+'</li>';
					});
					$("#msg").html('<ul class="errors-list">'+htmlMsg+'</ul>');
					$("#msg").show();
				}
			}
		});
	});
});

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

function onStaff() {
	$("#StaffClient").val(3);
	$('#staffid').addClass('active');
	$('#clientid').removeClass('active');
	$('#designationid').show();
	$('#countryid').hide();
	$("#designation").prop('required', true);
}

function onClient() {
	$("#StaffClient").val(2);
	$('#staffid').removeClass('active');
	$('#clientid').addClass('active');
	$('#designationid').hide();
	$('#countryid').show();
	$("#designation").prop('required', false);
}

function onCountry() {
    $.ajax({
		type : 'POST',
		url: base_url + '/getCountry',
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
