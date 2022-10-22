"use strict";

var $ = jQuery.noConflict();
var RecordId = '';
var language_code = '';
var onDataTable;

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	
	
	$(".tabs-nav li a.active").removeClass("active");
	$("#languages-nav").addClass("active");	
	$("#tabId-1").addClass("active");	
	
    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });

 	onDataTable = $('#DataTableId').DataTable({
        processing: true,
        serverSide: true,
		responsive: true,
		bSort: true,
		order: [[1, "ASC"]],
		language: {
			url: DataTableLanFile
		},		
		ajax: {
			url: base_url + '/backend/getLanguagesData',
			dataType: "json",
			type: "POST",
			data:{},			
		},
		columns: [{
				data: 'serialno',
				name: 'serialno',
				className: "text-center",
				sWidth: "5%",
				orderable: false,
				render: function (data, type, row, meta) {
					return  meta.row + meta.settings._iDisplayStart + 1;
				}
			}, {
				data: 'language_code',
				name: 'language_code',
				sWidth: "20%"
			}, {
				data: 'language_name',
				name: 'language_name',
				sWidth: "43%"
			},{
				data: 'language_default',
				name: 'language_default',
				sWidth: "20%",
				className: "text-center",
				orderable: false,
				render: function (data, type, row, meta) {
					if(row.language_default == 1){
						return '<span class="enable_btn">'+TEXT['Enable']+'</span>';
					}else{
						return '<span class="disable_btn">'+TEXT['Disable']+'</span>';
					}
				}
			},{
				data: 'action',
				name: 'action',
				sWidth: "15%",
				className: "text-center",
				orderable: false,
				render: function (data, type, row, meta) {
					if(row.language_code == 'en'){
						return "<a class='editIconBtn' title='"+TEXT['Edit']+"' href='javascript:void(0);'><i class='fa fa-edit'></i></a>"
					}else{
						return "<a class='editIconBtn' title='"+TEXT['Edit']+"' href='javascript:void(0);'><i class='fa fa-edit'></i></a>"
							+ "<a class='deleteIconBtn' title='"+TEXT['Delete']+"' href='javascript:void(0);'><i class='fa fa-remove'></i></a>"; 
					}
				}
			}
		]
	});
	
	//This line error mode off
	$.fn.dataTable.ext.errMode = 'throw';
	
	$('#DataTableId').on('click', 'tr', function (e) {
		e.preventDefault();
		var cColumn = e.originalEvent.target;
		var className = cColumn.className;
		var data = onDataTable.row(this).data();

		if ((className == 'editIconBtn')||(className == 'fa fa-edit')){
			RecordId = data.id;

			var msg = TEXT["Do you really want to edit this record"];
			onCustomModal(msg, "onLoadLanguageEditData");
		}
		
		if((className=='deleteIconBtn')||(className=='fa fa-remove')){
			RecordId = data.id; 
			language_code = data.language_code;
			
			var msg = TEXT["Do you really want to delete this record"];		                	  
			onCustomModal(msg, "onDeleteLanguage");
		}
	});
	
	$("#tw-loader").hide();
	
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
	RecordId = '';
	$('#language_code').prop('readonly', false);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
	$('#language_code').prop('readonly', true);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();	
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
		url: base_url + '/backend/saveLanguagesData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
				$("#DataTableId").dataTable().fnDraw();
				onListPanel();
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function onLoadLanguageEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getLanguageById',
		data: 'RecordId=' + RecordId,
		success: function (response) {
			var datalist = response;
			
			$("#RecordId").val(datalist.id);
			$("#language_code").val(datalist.language_code);
			$("#old_language_code").val(datalist.language_code);
			$("#language_name").val(datalist.language_name);

            if (datalist.language_default == 1) {
                document.getElementById("language_default").checked = true;
            } else {
                document.getElementById("language_default").checked = false;
            }
			
			onEditPanel();
        }
    });
}

//Language Delete
function onDeleteLanguage() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteLanguage',
		data: 'RecordId='+RecordId+'&language_code='+language_code,
		success: function (response) {		
            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				onSuccessMsg(msg);
				$("#DataTableId").dataTable().fnDraw();
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

