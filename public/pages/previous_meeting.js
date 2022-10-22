"use strict";

var $ = jQuery.noConflict();
var PageNumber = 0;
var onDataTable_Meeting;

$('#Meeting_TableId').on( 'page.dt', function () {
	var info = onDataTable_Meeting.page.info();
	PageNumber = info.page;
});

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(".tabs-nav li a.active").removeClass("active");
	$("#zoom-meeting").addClass("active");	
	$("#tabId-2").addClass("active");	

	$("#Meeting_TableId_filter").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#Meeting_TableId tbody tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	onDataTable_Meeting = $('#Meeting_TableId').DataTable({
		"responsive": true,
		"processing": true,
		"serverSide": true,
		"bSort": true,
		"sorting": [],
		"bDestroy": true,
		"bInfo": true,
		"bPaginate": true,
		"searching": false,
		"aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength": 25,
		"sPaginationType": "full_numbers",
		language: {
			url: DataTableLanFile
		},		
		"ajax": {
			"url": base_url + '/backend/getPreviousMeetingDataLoad',
			"type": "POST",
			"data": function (data) {
				data["params"] = {
					"page_number": PageNumber
				}
			}
		},
		columns: [{
				data: null,
				className: "dt-center",
				sWidth: "5%",
				"searchable": false,
				"orderable": false,
				render: function (data, type, row, meta) {
					return  meta.row + meta.settings._iDisplayStart + 1;
				}
			}, {			
				data: null,
				"orderable": false,
				sWidth: "50%",
				className: "dt-left",
				render: function (data, type, row, meta) {

					var Meeting = '<strong>'+TEXT['Meeting Topic']+'</strong>: '+data.topic;
						Meeting += '<br/><strong>'+TEXT['Meeting ID']+'</strong>: '+data.id;
						Meeting += '<br/><strong>'+TEXT['Start Time']+'</strong>: '+data.start_time;
						Meeting += '<br/><strong>'+TEXT['Time Zone']+'</strong>: '+data.timezone;
						Meeting += '<br/><strong>'+TEXT['Duration']+'</strong>: '+data.duration+' '+TEXT['minutes'];
						
					return Meeting;
				}
			}, {			
				data: null,
				className: "dt-center",
				sWidth: "17%",
				"searchable": false,
				"orderable": false,
				render: function (data, type, row, meta) {
					var Invitation = '';
					if(data.id !=''){
						Invitation = '<a onclick="getMeetingInvitation('+data.id+')" title="'+TEXT['Meeting Invitation']+'" href="javascript:void(0);" class="start-request">'+TEXT['Invitation']+'</a>';
					}
					return Invitation;
				}
			}, {			
				data: null,
				className: "dt-center",
				sWidth: "18%",
				"searchable": false,
				"orderable": false,
				defaultContent: "<a class='start-request StartMeeting' title='"+TEXT['Join Meeting']+"' href='javascript:void(0);'>"+TEXT['Join Meeting']+"</a>"
			}, {
				data: null,
				sWidth: "10%",
				className: "dt-center",
				"searchable": false,
				"orderable": false,
				"bVisible": true,
				defaultContent: "<a class='deleteIconBtn' title='" + TEXT['Delete'] + "' href='javascript:void(0);'><i class='fa fa-remove'></i></a>"
			}
		]
	});
	
	//This line error mode off
	$.fn.dataTable.ext.errMode = 'throw';
	
	$('#Meeting_TableId').on('click', 'tr', function (e) {
		e.preventDefault();
		var cColumn = e.originalEvent.target;
		var className = cColumn.className;
		var data = onDataTable_Meeting.row(this).data();

		if (className == 'start-request StartMeeting'){
			if(data.join_url !=''){
				window.open(data.join_url);
			}
		}
		
		if((className=='deleteIconBtn')||(className=='fa fa-remove')){
			MeetingId = data.id; 

			var msg = TEXT["Do you really want to delete this record"];		                	  
			onCustomModal(msg, "onDeleteUpcomingMeeting");
		}
		
	});
	
	$("#tw-loader").hide();
});

