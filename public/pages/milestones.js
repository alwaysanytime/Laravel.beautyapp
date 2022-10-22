"use strict";

var $ = jQuery.noConflict();
var RecordId = '';
var payment_method = '';
var client_id = 0;
var validCardNumer = 0;
$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$("#deadline").datetimepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		todayBtn: true,
		minView: 2
	});

	document.getElementById('printButton').addEventListener ("click", print);

	$("#cardInformation").hide();
	$('#name_on_card').attr('data-required', false);
	$('#ClientEmail').attr('data-required', false);
	$('#ClientPhone').attr('data-required', false);

	$("#payment_method_id").change(function() {
		payment_method = $("#payment_method_id").val();
		if(payment_method == 'Card'){
			onClientInfo();
			$("#cardInformation").show();
			$('#name_on_card').attr('data-required', true);
			$('#ClientEmail').attr('data-required', true);
			$('#ClientPhone').attr('data-required', true);
		}else{
			$("#cardInformation").hide();
			$('#name_on_card').attr('data-required', false);
			$('#ClientEmail').attr('data-required', false);
			$('#ClientPhone').attr('data-required', false);
		}
    });

    $('#submit-form').click(function () {
        $("#DataEntry_formId").submit();
    });

	var onDataTable = $('#DataTableId').DataTable({
        processing: true,
        serverSide: true,
		responsive: true,
		bSort: true,
		order: [[1, "DESC"]],
		language: {
			url: DataTableLanFile
		},
		ajax: {
			url: base_url + '/backend/getMilestoneData',
			dataType: "json",
			type: "POST",
			data: function (data) {
				data.project_id = project_id
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
				data: "invoice_no",
				name: "invoice_no",
				orderable: true,
				sWidth: "12%",
				className: "dt-center"
			}, {
				data: "title",
				name: "title",
				orderable: true,
				sWidth: "18%",
				className: "dt-left"
			}, {
				data: "deadline",
				name: "deadline",
				orderable: true,
				sWidth: "10%",
				className: "dt-center"
			}, {
				data: "amount",
				name: "amount",
				orderable: true,
				sWidth: "12%",
				className: "dt-right"
			}, {
				data: "payment_method",
				name: "payment_method",
				orderable: true,
				sWidth: "10%",
				className: "dt-center"
			}, {
				data: "payment_status",
				name: "payment_status",
				//data: "amount",
			//	name: "amount",
				orderable: false,
				sWidth: "10%",
				className: "dt-center",
				render: function (data, type, row, meta) {
					var payment_status = '';
					if(row.payment_status_id == 1 ){
						payment_status = '<span class="pstatus completed w-100" style="padding:5px 15px;display:block;">'+row.payment_status+'</span>';
					}else{
						payment_status = '<span class="pstatus expirydate w-100" style="padding:5px 15px;display:block;">'+row.payment_status+'</span>';
					}
					return payment_status;
				}
			}, {
				data: "id",
				name: "id",
				className: "dt-center",
				sWidth: "13%",
				orderable: false,
				render: function (data, type, row, meta) {
					var InvoiceView = '';
					if(row.id !=''){
						InvoiceView = '<a onclick="onInvoiceView('+row.id+');" href="javascript:void(0);" class="pstatus completed w-100" style="padding:5px 15px;display:block;">'+TEXT['View invoice']+'</a>';
					}
					return InvoiceView;
				}
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

		if ((className == 'editIconBtn')||(className == 'fa fa-edit')){
			var data = onDataTable.row(this).data();

			RecordId = data.id;

			var msg = TEXT["Do you really want to edit this record"];
			onCustomModal(msg, "onLoadEditData");
		}

		if((className=='deleteIconBtn')||(className=='fa fa-remove')){
			var data = onDataTable.row( this ).data();

			RecordId = data.id;

			var msg = TEXT["Do you really want to delete this record"];
			onCustomModal(msg, "onConfirmWhenDelete");
		}
	});

	onProjectName();
	onPaymentStatus();
	onPaymentMethod();

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
	$("#payment_status_id").val(1).trigger("chosen:updated");
	$("#payment_method_id").val('Bank').trigger("chosen:updated");
	$("#cardInformation").hide();
	$('#name_on_card').attr('data-required', false);
	$('#ClientEmail').attr('data-required', false);
	$('#ClientPhone').attr('data-required', false);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function print() {
	printJS({
		printable: 'printElement',
		type: 'html',
		targetStyles: ['*']
	});
}

function onProjectName(){
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getProjectName',
		data: 'project_id=' + project_id,
		success: function (response) {
			var datalist = response;
			if(datalist.project_name !=null){
				$("#project_name").html(datalist.project_name);
			}else{
				$("#project_name").html('');
			}
			if(datalist.client_id !=null){
				client_id = datalist.client_id;
			}else{
				client_id = 0;
			}

			$("#tw-loader").hide();
        }
    });
}

function onClientInfo() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getClientInfo',
		data: 'client_id=' + client_id,
		success: function (response) {
			var datalist = response;

			if(datalist.name !=null){
				$("#name_on_card").val(datalist.name);
			}else{
				$("#name_on_card").val('');
			}

			if(datalist.email !=null){
				$("#ClientEmail").val(datalist.email);
			}else{
				$("#ClientEmail").val('');
			}

			if(datalist.phone !=null){
				$("#ClientPhone").val(datalist.phone);
			}else{
				$("#ClientPhone").val('');
			}
        }
    });
}

function onPaymentStatus() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getPaymentStatusList',
		success: function (response) {
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.payment_status + '</option>';
			});

			$("#payment_status_id").html(html);
			$("#payment_status_id").chosen();
			$("#payment_status_id").trigger("chosen:updated");
        }
    });
}

function onPaymentMethod() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getPaymentMethodList',
		success: function (response) {
			var datalist = response;
			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<option value="' + obj.payment_method + '">' + obj.payment_method + '</option>';
			});

			$("#payment_method_id").html(html);
			$("#payment_method_id").chosen();
			$("#payment_method_id").trigger("chosen:updated");
        }
    });
}

function onConfirmWhenAddEdit() {

	if(payment_method == 'Card'){
		if(isenable_stripe == 1){
			if(validCardNumer == 0){
				$("#card-errors").text(TEXT['Please type valid card number']);
				return;
			}
		}
	}

	if(payment_method == 'Card'){
		if(isenable_stripe == 0){
			return;
		}
	}

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveMilestoneData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {

			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				$("#DataTableId").dataTable().fnDraw();

				if(payment_method == 'Card'){
					if(isenable_stripe == 1){
						if(response.intent != ''){
							onConfirmPayment(response.intent, msg);
						}
					}
				}else{
					onSuccessMsg(msg);
					onListPanel();
				}

			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMilestoneById',
		data: 'id=' + RecordId,
		success: function (response) {
			var data = response;

			$('#RecordId').val(data.id);
			$('#title').val(data.title);
			$('#amount').val(data.amount);
			$('#deadline').val(data.deadline);
			$("#payment_status_id").val(data.payment_status_id).trigger("chosen:updated");
			$('#project_id').val(data.project_id);

			onEditPanel();
        }
    });
}

function onConfirmWhenDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteMilestone',
		data: 'id=' + RecordId,
		success: function (response) {

            var msgType = response.msgType;
            var msg = response.msg;

            if (msgType == "success") {
				$("#DataTableId").dataTable().fnDraw();
				onSuccessMsg(msg);
            } else {
                onErrorMsg(msg);
            }
        }
    });
}

function onInvoicePdf() {
	var payment_id = $("#payment_id").val();
	window.open(base_url + '/backend/invoice-pdf/'+payment_id, '_blank');
}

function onInvoiceView(id) {
	$("#payment_id").val(id);

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getInvoice',
		data: 'id=' + id,
		success: function (response) {
			var data = response;

			var name = '';
			if(data.name != null){
				name = data.name;
			}
			$("#client_inv").html(name);

			var address = '';
			if(data.address != null){
				address = data.address;
			}
			$("#address_inv").html(address);

			var project_name = '';
			if(data.project_name != null){
				project_name = '<strong>'+TEXT['Project Name']+'</strong>: '+data.project_name;
			}
			$("#project_inv").html(project_name);

			var deadline = '';
			if(data.deadline != null){
				deadline = '<strong>'+TEXT['Due Date']+'</strong>: '+data.deadline;
			}
			$("#date_inv").html(deadline);

			var invoice_no = '';
			if(data.invoice_no != null){
				invoice_no = '<strong>'+TEXT['Invoice No']+'</strong>: '+data.invoice_no;
			}
			$("#invoice_no_inv").html(invoice_no);

			var payment_method = '';
			if(data.payment_method != null){
				payment_method = '<strong>'+TEXT['Payment Method']+'</strong>: '+data.payment_method;
			}
			$("#payment_method").html(payment_method);

			var payment_status = '';
			if(data.payment_status != null){
				if(data.payment_status_id == 1){
					payment_status = '<strong>'+TEXT['Status']+'</strong>: '+'<span class="status-paid">'+data.payment_status+'</span>';
				}else{
					payment_status = '<strong>'+TEXT['Status']+'</strong>: '+'<span class="status-unpaid">'+data.payment_status+'</span>';
				}
			}
			$("#payment_status_inv").html(payment_status);

			var title = '';
			if(data.title != null){
				title = data.title;
			}
			$("#title_inv").html(title);

			var amount = '';
			var Subtotal = '';
			if(data.amount != null){
				amount = TEXT['Currency']+data.amount;
				Subtotal = TEXT['Subtotal']+': '+TEXT['Currency']+data.amount;
			}
			$("#amount_inv").html(amount);
			$("#Subtotal_inv").html(Subtotal);

			$('#InvoiceView_Id').modal('show');
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

