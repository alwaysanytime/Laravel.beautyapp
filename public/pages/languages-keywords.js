"use strict";

var $ = jQuery.noConflict();
var RecordId = '';
var onDataTable;
var initload = 0;

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	onLanguageCombo();
	
	$(".tabs-nav li a.active").removeClass("active");
	$("#languages-nav").addClass("active");	
	$("#tabId-2").addClass("active");	
	
    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });
	
	$('#language_code').change(function () {
		$("#DataTableId").dataTable().fnDraw();
	});
});

function onLanguageCombo() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getLanguageCombo',
		success: function (response) {
			var datalist = response;
			var html = '';
			var languageDefault = '';
			$.each(datalist, function (key, obj) {
				if(obj.language_default == 1){
					languageDefault = obj.language_code;
				}
				html += '<option value="' + obj.language_code + '">' + obj.language_name + '</option>';
			});
			
			$("#language_code").html(html);
			$("#language_code").chosen();
			$("#language_code").val(languageDefault).trigger("chosen:updated");
			
			loadDataTable();
			
			//Preloader and content
			$('#tw-content').show();
			$('#tw-loader').hide();
        }
    });
}

//Language Keywords
function loadDataTable() {

	if(initload > 0){
		$("#DataTableId").dataTable().fnDraw();
	}
	
	if(initload == 0){
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
				url: base_url + '/backend/getLanguageKeywordsData',
				dataType: "json",
				type: "POST",
				data: function (data) {
					data.language_code = $('#language_code').val()
				},			
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
					data: 'language_key',
					name: 'language_key',
					sWidth: "40%"
				}, {
					data: 'language_value',
					name: 'language_value',
					sWidth: "43%"
				},{
					data: 'action',
					name: 'action',
					sWidth: "15%",
					className: "text-center",
					orderable: false,
					render: function (data, type, row, meta) {
						return "<a class='editIconBtn' title='"+TEXT['Edit']+"' href='javascript:void(0);'><i class='fa fa-edit'></i></a>"
						+ "<a class='deleteIconBtn' title='"+TEXT['Delete']+"' href='javascript:void(0);'><i class='fa fa-remove'></i></a>"; 
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
				onCustomModal(msg, "onLoadEditData");
			}
			
			if((className=='deleteIconBtn')||(className=='fa fa-remove')){
				RecordId = data.id; 
				
				var msg = TEXT["Do you really want to delete this record"];		                	  
				onCustomModal(msg, "onDelete");
			}
		});
		
		initload++;
	}
}

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

function onListPanel() {
	$('.filter').show();
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	RecordId = '';
	$('.filter').hide();
	$('#language_key').prop('readonly', false);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
	$('.filter').hide();
	$('#language_key').prop('readonly', true);
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
		url: base_url + '/backend/saveLanguageKeywordsData',
		data: $('#DataEntry_formId').serialize()+'&language_code='+$("#language_code").val(),
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

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getLanguageKeywordsById',
		data: 'RecordId=' + RecordId,
		success: function (response) {
			var datalist = response;
			
			$("#RecordId").val(datalist.id);
			$("#language_key").val(datalist.language_key);
			$("#language_value").val(datalist.language_value);

			onEditPanel();
        }
    });
}

//Language Delete
function onDelete() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteLanguageKeywords',
		data: 'RecordId='+RecordId+'&language_code='+$("#language_code").val(),
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

