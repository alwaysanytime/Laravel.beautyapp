"use strict";
var $ = jQuery.noConflict();

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	if(roleid == 1){
		$("#ClientGridHideShow").show();
	}else{
		$("#ClientGridHideShow").hide();
	}
	
	onLoadCountProjects();
	onLoadCountTasks();
	onLoadStaffsPieChart();

 	var onDataTableProject = $('#DataTableIdProject').DataTable({
        processing: true,
        serverSide: true,
		responsive: true,
		bSort: true,
		order: [[0, "ASC"]],
		language: {
			url: DataTableLanFile
		},		
		ajax: {
			url: base_url + '/backend/getProjectList',
			dataType: "json",
			type: "POST",
			data: function (data) {
				data.roleid = roleid,
				data.userid = userid
			},			
		},
		columns: [{
				data: 'project_name',
				name: 'project_name',
				sWidth: "25%",
				className: "text-left"
			}, {
				data: 'createby_photo',
				name: 'createby_photo',
				sWidth: "12%",
				className: "text-center",
				render: function (data, type, row, meta) {
					if(row.createby_photo != null){
						return '<ul class="facelist"><li><img title="'+row.createby_name+'" src="'+public_path+'/media/'+row.createby_photo+'"></li></ul>';
					}else{
						return '<ul class="facelist"><li><img title="'+row.createby_name+'" src="'+public_path+'/assets/images/default.png"></li></ul>';
					}
				}
			}, {
				data: 'Photos',
				name: 'Photos',
				sWidth: "18%",
				className: "text-left"
			}, {
				data: 'client_photo',
				name: 'client_photo',
				sWidth: "5%",
				className: "text-center",
				render: function (data, type, row, meta) {
					if(row.client_photo != null){
						return '<ul class="facelist"><li><img title="'+row.client_name+'" src="'+public_path+'/media/'+row.client_photo+'"></li></ul>';
					}else{
						return '<ul class="facelist"><li><img title="'+row.client_name+'" src="'+public_path+'/assets/images/default.png"></li></ul>';
					}
				}
			}, {
				data: 'start_date',
				name: 'start_date',
				sWidth: "10%",
				className: "text-center"
			}, {
				data: 'end_date',
				name: 'end_date',
				sWidth: "10%",
				className: "text-center"
			},{
				data: 'status_name',
				name: 'status_name',
				sWidth: "10%",
				className: "text-center",
				render: function (data, type, row, meta) {
					return '<span class="pstatus '+row.statusClass+'">'+row.status_name+'</span>';
				}
			},{
				data: 'id',
				name: 'id',
				sWidth: "10%",
				className: "text-center",
				render: function (data, type, row, meta) {
					if(row.id != ''){
						return '<a title="'+TEXT['Milestones View']+'" href="'+base_url + '/backend/milestones/'+row.id+'" class="btn green-btn" style="padding:5px 15px;"><i class="fa fa-paper-plane-o"></i> '+TEXT['View']+'</a>';
					}
				}
			}
		]
	});	
	
 	var onDataTableClient = $('#DataTableIdClient').DataTable({
        processing: true,
        serverSide: true,
		responsive: true,
		bSort: true,
		order: [[0, "ASC"]],
		language: {
			url: DataTableLanFile
		},		
		ajax: {
			url: base_url + '/backend/getDashboardClientList',
			dataType: "json",
			type: "POST",
			data: function (data) {
				data.roleid = roleid,
				data.userid = userid
			},			
		},
		columns: [{
				data: 'name',
				name: 'name',
				sWidth: "31%",
				render: function (data, type, row, meta) {
					if(row.name != null){
						var name = '<strong>Client: </strong>'+row.name;
					}else{
						var name = '';
					}
					
					if(row.country_name != null){
						var country_name = '<strong>Country: </strong>'+row.country_name;
					}else{
						var country_name = '';
					}
					return name+'<br/>'+country_name;
				}
			}, {
				data: 'email',
				name: 'email',
				sWidth: "31%",
				render: function (data, type, row, meta) {
					if(row.email != null){
						var email = '<strong>Mail: </strong>'+row.email;
					}else{
						var email = '';
					}
					
					if(row.phone != null){
						var phone = '<strong>Phone: </strong>'+row.phone;
					}else{
						var phone = '';
					}
					
					return email+'<br/>'+phone;
				}
			},{
				data: 'skype_id',
				name: 'skype_id',
				sWidth: "31%",
				render: function (data, type, row, meta) {
					if(row.skype_id != null){
						var skype_id = '<strong>Skype: </strong>'+row.skype_id;
					}else{
						var skype_id = '';
					}
					
					if(row.facebook_id != null){
						var facebook_id = '<strong>FB: </strong>'+row.facebook_id;
					}else{
						var facebook_id = '';
					}
					
					return skype_id+'<br/>'+facebook_id;
				}
			},{
				data: 'photo',
				name: 'photo',
				sWidth: "7%",
				render: function (data, type, row, meta) {
					if(row.photo != null){
						return '<ul class="facelist"><li><img title="'+row.name+'" src="'+public_path+'/media/'+row.photo+'"></li></ul>';
					}else{
						return '<ul class="facelist"><li><img title="'+row.name+'" src="'+public_path+'/assets/images/default.png"></li></ul>';
					}
				}
			}
		]
	});
	
	//This line error mode off
	$.fn.dataTable.ext.errMode = 'throw';
	
});

function onLoadCountProjects() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTotalProjects',
        "data": 'roleid='+roleid + '&userid='+userid,
		success: function (response) {		
			var data = response.dataDiv;
			var dataList = response;

			$("#TotalProjects").text(data[0].TotalProject);
			$("#InprogressProjects").text(data[0].Inprogress);
			$("#CompletedProjects").text(data[0].Completed);
			$("#TimeOut").text(data[0].TimeOut);

			var ProjectsPieChart = new Chart('pie_chart_projects', {
				type: 'pie',
				data: {
					datasets: [{
						data: dataList['data'],
						backgroundColor: dataList['backgroundColor'],
					}],
					labels: dataList['labels']
				},
				options: {
					responsive: true
				}
			});
        }
    });
}

function onLoadCountTasks() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getTotalTasks',
        "data": 'roleid='+roleid + '&userid='+userid,
		success: function (response) {
			var data = response;
			$("#TotalTasks").text(data[0].TotalTasks);
			$("#DoingTasks").text(data[0].DoingTasks);
			$("#TimeoutTasks").text(data[0].TimeoutTasks);
			$("#CompletedTasks").text(data[0].CompletedTasks);
        }
    });
}

function onLoadStaffsPieChart() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getStaffStatus',
		success: function (response) {
			var dataList = response;
			var ProjectsPieChart = new Chart('pie_chart_staffs', {
				type: 'pie',
				data: {
					datasets: [{
						data: dataList['data'],
						backgroundColor: dataList['backgroundColor'],
					}],
					labels: dataList['labels']
				},
				options: {
					responsive: true
				}
			});
			$("#tw-loader").hide();
        }
    });
}
