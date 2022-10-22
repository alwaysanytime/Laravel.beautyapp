@extends('layouts.backend')

@section('content')

    <?php
    $customer_info = "";
    foreach ($data_app as $users) {
        if (isset($_POST["customer_id"])) {
            if ($_POST["customer_id"] == $users->customerid)
                $customer_info = $users->subject;
        }
    }
    ?>

    @push('style')
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('public/assets/css/brand.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('public/assets/css/dashboard-appointment.css')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" >
        <link rel="stylesheet" href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" >
        
        <style>
        .modal-lg {
            max-width: 80% !important;
        }
        .table-height {
            height: 250px;

        }
    </style>

    @endpush

    <div id="table_close_on_contract" class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="dash-heading mb-10">
                        <div class="row">
                            <div class="col-md-8 mb-10">
                                <h3 style="display: inline-block; vertical-align: middle;padding-left: 15px"><?=$customer_info?></h3>
                            </div>
                            <div style="margin-left: auto; margin-right: 0; padding-right: 15px;">
                                <button style="background-color: #f44336; height: 45px; width: 250px" onclick="history.back()" class="btn"><i class="fa fa-arrow-left fa-lg"></i> Zurück zum Kalender </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mx-auto">
                    <div class="card rounded shadow border-0">
                        <div class="card-body p-2 bg-white rounded">
                            <div class="table-responsive">
                                <table id="example_agreements" style="width:100%" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th hidden>id</th>
                                        <th>Vereinbarung</th>
                                        <th id="order_on_refresh">Datum</th>
                                        <th>Kategorie</th>
                                        <th>Areale</th>
                                        <th>Notiz</th>
                                        <th>Gesamtbetrag</th>
                                        <th>Bezahlt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0;
                                    foreach ($data_agr as $agreements):?>
                                    <tr>
                                        <td hidden><?= $i++; ?></td>
                                        <td><?=$agreements->agreementid?></td>
                                        <td><?=gmdate("d.m.Y", strtotime($agreements->agreedate))?></td>
                                        <td><?=$agreements->category?></td>
                                        <td><?=$agreements->areas?></td>
                                        <td><?=$agreements->agrnotes?></td>
                                        <td><?=$agreements->agrprice?></td>
                                        <td><?=$agreements->payment?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="padding-top: 25px" class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="card rounded shadow border-0">
                        <div class="card-body p-2 bg-white rounded">
                            <div class="table-responsive">
                                <table id="example_appointments" style="width:100%" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th hidden>id</th>
                                        <th id="order_on_refresh">Startzeit - Endzeit</th>
                                        <th>Therapeut</th>
                                        <th>Notiz</th>
                                        <th>Vereinbarung</th>
                                        <th>Areale</th>
                                        <th>Preis</th>
                                        <th>Bezahlt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0;
                                    foreach ($data_app as $appointment):?>
                                    <tr id = "treatment">
                                        <td hidden><?= $i++?></td>
                                        <td><?=gmdate("d.m.Y H:i:s", strtotime($appointment->starttime))?>{{--<hr><?=gmdate("H:i:s", strtotime($appointment->endtime))?>--}}</td>
                                        <td><?=$appointment->therapist?></td>
                                        <td><?=$appointment->notes?></td>
                                        <td><?=$appointment->agreementid?></td>
                                        <td><?=$appointment->treatments?></td>
                                        <td><?=$appointment->price?></td>
                                        <td><?=$appointment->paidamount?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Behandlungen</h5>
            <button type="button" class="close cancelbtn" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Vereinbarung</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="selectAgreement">
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Date</label>
                                <div class="col-sm-10">
                                    <input id="datepicker" width="276" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Startzeit</label>
                                <div class="col-sm-4">
                                    <input id="starttimepicker"/>
                                </div>
                                <label for="staticEmail" class="col-sm-2 col-form-label">Endzeit</label>
                                <div class="col-sm-4">
                                    <input id="endtimepicker"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Kategorie</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="selectCategory">
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Terapent</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="selectTherapist" placeholder="Select Terapent">
                                </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Areale</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputAreale" placeholder="Areale">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">Notiz</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="textNote" rows="6"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2 col-form-label">

                                </div>
                                <div class="form-check col-sm-10">
                                    <input class="form-check-input" type="checkbox" value="" id="checksms">
                                    <label class="form-check-label" for="invalidCheck">
                                    SMS-Erinnerung senden
                                </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2 col-form-label">

                                </div>
                                <div class="form-check col-sm-10">
                                    <input class="form-check-input" type="checkbox" value="" id="checknopayment">
                                    <label class="form-check-label" for="invalidCheck">
                                    Kunde muss die Behandlung nicht bezahlen
                                </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lastupdate" class="col-sm-2 col-form-label">Geändert</label>
                                <label id = "lblupdated" class="col-sm-10 col-form-label">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Anwendungen</label>
                                <div class = "">
                                    <table id = "tbltreatment" class="table table-bordered table-responsive table-height">
                                        <thead>
                                            <tr>
                                                <th scope="col">Anwendung</th>
                                                <th scope="col">ms</th>
                                                <th scope="col">Joulde</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <h3 for="exampleFormControlTextarea1">Zahlung</h3>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Gemsamtbetrag</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="inputTotalamount" placeholder="Gemsamtbetrag(Total amount)">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Zahlart</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paymethodOptions" id="radioBar" value = 0>
                                        <label class="form-check-label" for="inlineRadio1">Bar</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paymethodOptions" id="radioEC" value = 1>
                                        <label class="form-check-label" for="inlineRadio2">EC</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Heute falliger preis</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="inputPaidamount" placeholder="Heute falliger preis(today due price)">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id = "savebtn" >Speichern</button>
            <button type="button" class="btn btn-secondary cancelbtn" >Abbrechen</button>
        </div>
        </div>
    </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('public/assets/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('public/assets/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    

    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
   
        $(function () {
            "use strict";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function () {
                var customer_id = <?php echo json_encode($customer_id);?>;
                var appArray = <?php echo json_encode($data_app);?>;
                var agrArray = <?php echo json_encode($data_agr);?>;
                var usersArray = <?php echo json_encode($data_users);?>;
                var apptypesArray = <?php echo json_encode($data_apptypes);?>;
                var areasArray = <?php echo json_encode($data_areas);?>;
                var RowIndex = 0;
                var appointmentId = 0;
                var starttime = "";
                var endtime = "";
                var therapist = "";
                var textNote = "";
                var inputTotalamount = 0;
                var inputPaidamount = 0;
                var apptype = 0;
                var agreementid = 0;
                var paymethod = 0;
                var sendsms = 0;
                var nopayment = 0;

                var selectAgreementDom = "";
                var selectCategoryDom = "";
                var selectTerapentDom = "";
                var lastuserupdated = "";
                
                $('#example_appointments').DataTable({
                    "pageLength": 100,
                    "ordering": false,
                    "searching": false
                });
                // $('#example_agreements').DataTable({
                //     "sZeroRecords": "Keine Behandlungen vorhanden."
                // });

                $('#example_appointments tr').dblclick(function(){
                    var currentRow=$(this).closest("tr"); 
                    RowIndex=currentRow.find("td:eq(0)").html(); // get current row 1st table cell TD value
                    var i = 0;

                    appointmentId = appArray[RowIndex]['id']

                    var agreedate = "";
                    while(i < agrArray.length){
                        selectAgreementDom += "<option value = '";
                        selectAgreementDom += i;
                        selectAgreementDom += "'";
                        // if(i==RowIndex){
                        //     selectAgreementDom += " selected ";
                        // }
                        selectAgreementDom +=">";
                        selectAgreementDom += agrArray[i]['agreementid'];
                        selectAgreementDom += " ";
                        agreedate = new Date(agrArray[i]['agreedate']);
                        selectAgreementDom += agreedate.toLocaleDateString();
                        selectAgreementDom += " ";
                        selectAgreementDom += agrArray[i]['areas'];
                        selectAgreementDom += "</option>";
                        i++;
                    }
                    $( "#selectAgreement" ).append(selectAgreementDom);

                    starttime = new Date(appArray[RowIndex]['starttime']);
                    endtime = new Date(appArray[RowIndex]['endtime']);
                    $("#datepicker").val(starttime.toLocaleDateString());
                    $("#starttimepicker").val(starttime.getHours()+":"+starttime.getMinutes());
                    $("#endtimepicker").val(endtime.getHours()+":"+endtime.getMinutes());
                    
                    while (i < apptypesArray.length) {
                        // selectCategoryDom += "<option style='background-color: #";//><div style='background-color: hsl(0.15turn, 50%, 75%);'><code>hsl(0.15turn, 50%, 75%)</code></div><div style='background-color: hsl(0.15turn, 50%, 75%);'>&nbsp;</div>";
                        // selectCategoryDom += apptypesArray[i]['color'].toString(16);
                        // selectCategoryDom += "'>";
                        selectCategoryDom += "<option value = '"
                        selectCategoryDom += i;
                        selectCategoryDom += "'";
                        if(i==RowIndex){
                            selectCategoryDom += " selected ";
                        }
                        selectCategoryDom +=">";
                        selectCategoryDom += apptypesArray[i]['Descr'];
                        selectCategoryDom += "</option>";
                        i++;
                    }
                    $( "#selectCategory" ).append(selectCategoryDom);

                    var i = 0;
                    
                    
                    while (i < appArray.length) {
                        selectTerapentDom += "<option>";
                        selectTerapentDom += appArray[i]['therapist'];
                        selectTerapentDom += "</option>";
                        i++;
                    }

                    $("#inputAreale").val(appArray[RowIndex]['treatments']);

                    therapist = appArray[RowIndex]['therapist'];
                    $("#selectTherapist").val(therapist);
                    
                    textNote = appArray[RowIndex]['notes'];
                    $("#textNote").val(textNote);

                    sendsms = appArray[RowIndex]['sendsms'];
                    if(sendsms == 1){
                        $('#checksms').prop('checked', true);
                    }
                    
                    nopayment = appArray[RowIndex]['nopayment'];
                    if(nopayment == 1){
                        $('#checknopayment').prop('checked', true);
                    }
                    
                    if(appArray[RowIndex]['lastuser'] != usersArray.length){
                        lastuserupdated = "null,am" + appArray[RowIndex]['lastupdate'];
                    } else {
                        lastuserupdated = usersArray[appArray[RowIndex]['lastuser']]['name'] + ",am" + appArray[RowIndex]['lastupdate'];
                    }
                    
                    $("#lblupdated").text(lastuserupdated);

                    const treatmentArray = appArray[RowIndex]['treatments'].split("  ");
                    
                    var grouparray = groupArr(treatmentArray, 2);
                    var secondgroup = [];
                    var treatmentsArray = [];
                    var treatmentDOM = "";
                    i = 0;
                    while(i<grouparray.length){
                        var secondgroup = grouparray[i][1].split(", ");
                        var tempmsvalue = secondgroup[0].split(" ");

                        var tempjoudlevalue = secondgroup[1].split(" ");
                        treatmentsArray[i] = {
                            "treatment" : grouparray[i][0],
                            "ms" : tempmsvalue[0],
                            "joudle" : tempjoudlevalue[0],
                        }

                        treatmentDOM +="<tr><td><div class='form-check'><input class='form-check-input' type='checkbox' value='' id='checktreatment' checked><label id='lbltreatment' class='form-check-label' for='flexCheckChecked'>";
                        treatmentDOM += treatmentsArray[i].treatment;
                        treatmentDOM += "</label></div></td><td><input type='text' class= 'form-control' id='inputms' placeholder='Input ms' value = '";
                        treatmentDOM += treatmentsArray[i].ms;
                        treatmentDOM += "'></td><td><input type='text' class= 'form-control' id='inputjoudle' placeholder='Input joudle' value = '";
                        treatmentDOM += treatmentsArray[i].joudle;
                        treatmentDOM += "'></td></tr>";
                        i++;
                    }
                    
                    $('#tbltreatment > tbody:last-child').append(treatmentDOM);

                    inputTotalamount = appArray[RowIndex]['price'];
                    $("#inputTotalamount").val(inputTotalamount);

                    inputPaidamount = appArray[RowIndex]['paidamount'];
                    $("#inputPaidamount").val(inputPaidamount);

                    if(appArray[RowIndex]['paymethod'] == 0) {
                        $('#radioBar').prop( 'checked', true );
                    } else {
                        $('#radioEC').prop( 'checked', true );
                    }
                    $('#exampleModalCenter').modal('show')
                    

                });
                $("#savebtn").click(function(e){
                    e.preventDefault();

                    agreementid = $("#selectAgreement").val(); 
                    agreementid = agrArray[agreementid]['agreementid'];

                    var getendtime = $("#datepicker").val() + " "+$('#endtimepicker').val();
                    endtime = getendtime + ":00";

                    var getstarttime = $("#datepicker").val() + " "+$('#starttimepicker').val();
                    starttime = getstarttime + ":00";

                    therapist = $("#selectTherapist").val();
                    textNote =  $("#textNote").val();
                    inputTotalamount = $("#inputTotalamount").val();
                    inputPaidamount = $("#inputPaidamount").val();

                    sendsms = $('#checksms').is(":checked")? 1 : 0;
                    nopayment = $('#checknopayment').is(":checked")? 1 : 0;

                    apptype = $("#selectCategory").val(); 
                    apptype = apptypesArray[apptype]['id'];

                    paymethod = $("input[name='paymethodOptions']:checked").val();

                    var oTable = document.getElementById('tbltreatment');

                    var rowLength = oTable.rows.length;

                    //loops through rows    
                    var treatments = "";
                    var celltreatment = "";
                    var cellms = "";
                    var cellJoulde = "";

                    for (var i = 0; i < rowLength; i++){
                        var oCells = oTable.rows.item(i).cells;
                        
                        if($(oTable.tBodies[0].rows[i]).find('input:checkbox').prop('checked')){
                            celltreatment = $(oTable.tBodies[0].rows[i]).find('input:checkbox').next().text();
                            cellms = $(oTable.tBodies[0].rows[i]).find('input:text')[0].value;
                            cellJoulde = $(oTable.tBodies[0].rows[i]).find('input:text')[1].value;
                            treatments +=  celltreatment + "  " + cellms + " ms, " + cellJoulde + " Joule";
                            if(i != rowLength - 2){
                                treatments += "  ";
                            }
                        }
                    }
                    $.ajax({
                        type : 'POST',
                        url: base_url + '/backend/SaveAppointment',
                        data: {
                            agreementid:agreementid,
                            appointmentId:appointmentId,
                            starttime : starttime,
                            endtime : endtime,
                            therapist : therapist,
                            textNote : textNote,
                            inputTotalamount : inputTotalamount,
                            inputPaidamount : inputPaidamount,
                            treatments : treatments,
                            apptype : apptype,
                            paymethod : paymethod,
                            sendsms : sendsms,
                            nopayment : nopayment,
                        },
                        success: function (response) {
                            var msgType = response.msgType;
                            var msg = response.msg;
                            $('#exampleModalCenter').modal('hide');
                            if (msgType == "success") {
                                alert(msg);
                                
                            } else {
                                alert(msg);
                            }
                            location.reload(true);
                        }
                    });
                });
                $(".cancelbtn").click(function(){
                    $('#exampleModalCenter').modal('hide');
                    location.reload(true);
                });
                function groupArr(data, n) {
                        var group = [];
                        for (var i = 0, j = 0; i < data.length; i++) {
                            if (i >= n && i % n === 0)
                                j++;
                            group[j] = group[j] || [];
                            group[j].push(data[i])
                        }
                        return group;
                    }
            });
        });
    </script>
    <script>
        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });
        $('#starttimepicker').timepicker();
        $('#endtimepicker').timepicker();
    </script>
    <script !src="">
        $(document).ready(function () {
            var url_string = window.location.href;
            var splitted = url_string.split("/");
            if (splitted[splitted.length - 1] == "calendar-details") {
                var element_side = document.getElementById("side_close_on_contract");
                var element_main = document.getElementById("table_close_on_contract");
                var element_header = document.getElementById("header_close_on_contract");
            }
        });
    </script>
@endpush
