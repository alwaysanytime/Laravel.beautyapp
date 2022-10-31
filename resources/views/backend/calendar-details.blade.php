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

        #appointmentMenu {
            /* position: absolute; */
            display: none;
        }
        .wrap {
            width: 90%;
            display: block;
            margin: 0 auto;
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
                                <table id="details_agreements" style="width:100%" class="table table-striped table-bordered table-hover">
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
                                <table id="details_appointments" style="width:100%" class="table table-striped table-bordered table-hover">
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
                                    foreach ($data_app as $appointment):
                                    if($appointment->deleted == 1) { ?>
                                    <tr id="treatment" style="color: red;">
                                    <?php } else { ?>
                                    <tr id = "treatment">
                                    <?php } ?>
                                        <td hidden><?= $i++?></td>
                                        <td><?= $appointment->starttime//gmdate("d/m/Y H:i:s", strtotime())?>{{--<hr><?=gmdate("H:i:s", strtotime($appointment->endtime))?>--}}</td>
                                        <td><?=$appointment->therapist?></td>
                                        <td><?=$appointment->notes?></td>
                                        <td><?=$appointment->agreementid?></td>
                                        <td><?=$appointment->treatments?></td>
                                        <td><?=$appointment->price?></td>
                                        <td><?=$appointment->paidamount?></td>
                                    </tr>
                                    <?php  endforeach;?>
                                    </tbody>
                                </table>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="appointmentMenu">
                                    <a class="dropdown-item" id = "openAppointment" >Offnen(Open)</a>
                                    <a class="dropdown-item" id = "deleteAppointment" >Loschen(Delete)</a>
                                    <div class = "dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">unwiderruflich Löschen</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agreement-->
    <div class="modal fade" id="modalAgreement" tabindex="-1" role="dialog" aria-labelledby="modalAgreementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgreementTitle">Vereinbarung</h5>
                <button type="button" class="close cancelbtn" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-10">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Nr.</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputAgrNo" placeholder="Agreement Nr." disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Datum</label>
                            <div class="col-sm-10">
                                <input id="agrdatepicker" width="276" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Kategorie</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="selectAgrCategory">
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Terapent</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputAgrTherapist" placeholder="Select Terapent">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Areale</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputAgrArea" placeholder="Areale">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">Notiz</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="textAgrNote" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Gemsamtbetrag</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputAgrTotalamount" placeholder="Gemsamtbetrag(Total amount)">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lastupdate" class="col-sm-2 col-form-label">Geändert</label>
                            <label id = "lblAgrupdated" class="col-sm-10 col-form-label">Email</label>
                        </div>
                        
                        </div>
                    <div class="col-md-1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id = "updateAgreementbtn" >Speichern</button>
                <button type="button" class="btn btn-secondary cancelbtn" >Abbrechen</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Appointment-->
    <div class="modal fade" id="modalAppointment" tabindex="-1" role="dialog" aria-labelledby="modalAppointmentTitle" aria-hidden="true">
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
                                    <label for="staticEmail" class="col-sm-2 col-form-label">Datum</label>
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
                                        <input type="text" class="form-control" id="inputArea" placeholder="Areale">
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
                <button type="button" class="btn btn-primary" id = "updateAppointmentbtn" >Speichern</button>
                <button type="button" class="btn btn-secondary cancelbtn" >Abbrechen</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Agreement-->
    <div class="modal fade" id="modalDelAppointment" tabindex="-1" role="dialog" aria-labelledby="modalDeleteAppointTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteAppointTitle">Bestatigen</h5>
                <button type="button" class="close cancelbtn" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-10">
                        <p>Mochten Sit</p>
                        <p></p>
                        <p>Maximi</p>
                        <p>Modal body text goes here.</p>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Delete Type</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="selectDelTypes">
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id = "deleteAppointmentbtn" disabled>Speichern</button>
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
                var delTypesArray = <?php echo json_encode($data_deltypes);?>;
                
                var RowAgrIndex = 0;
                var RowAppIndex = 0;
                var selector = 0;

                var agrTable = "#details_agreements";
                var appTable = "#details_appointments";

                var agrID = 0;
                var agrNo = 0;
                var agrDate = "";
                var agrTherapist = "";
                var agrArea = "";
                var agrNote = "";
                var agrTotalamount = 0;
                var agrLastuserupdated = "";

                var appID = 0;
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

                var appdeltype = -1;

                var selectAgreementDom = "";
                var selectCategoryDom = "";
                var selectTerapentDom = "";
                var selectDelTypeDom = "";
                var lastuserupdated = "";

                

                
                $('#details_appointments').DataTable({
                    "pageLength": 100,
                    "ordering": false,
                    "searching": false
                });
                // $('#details_agreements').DataTable({
                //     "sZeroRecords": "Keine Behandlungen vorhanden."
                // });

                // function ReplaceAgrCellContent(find, replace)
                // {
                //     $("#details_agreements td:contains('" + find + "')").html(replace);
                // }

                $("#details_appointments tr").on('contextmenu', function(e) {
                    RowAppIndex = $(this).closest("tr").index();
                    selector = $(this).closest('tr');
                    
                    $('tr').css('box-shadow', 'none');
                    var top = e.pageY - 330;
                    var left = e.pageX - 205;
                    $(this).css('box-shadow', 'inset 1px 1px 0px 0px red, inset -1px -1px 0px 0px red');
                    $("#appointmentMenu").css({
                        display: "block",
                        top: top,
                        left: left
                    });
                    return false; //blocks default Webbrowser right click menu
                });

                $("#openAppointment").on('click', function(){
                    showAppointment();
                    $('#modalAppointment').modal('show');
                });
                
                $("#deleteAppointment").on('click', function(){
                    
                    showDelAppointment();
                    $('#modalDelAppointment').modal('show');
                });

                $("body").on("click", function() {
                    if ($("#appointmentMenu").css('display') == 'block') {
                        $(" #appointmentMenu ").hide();
                    }
                    $('td').css('box-shadow', 'none');
                });

                $("#appointmentMenu a").on("click", function() {
                    $(this).parent().hide();
                });
                
                function ReplaceCellContent(table, row, col, replace)
                {
                    $(table)[0].rows[row].cells[col].innerHTML = replace;
                }
                
                function ReplaceRowColor(table, row, color)
                {
                    $(table)[0].rows[row].style.color = color;
                }

                $('#details_agreements td').dblclick(function(){

                    RowAgrIndex = $(this).parent().index();

                    selector = $(this).closest('tr');
                    agrNo = selector.find('td:eq(1)').text();

                    agrID = agrArray[RowAgrIndex]['id'];
                    var i = 0;

                    // agrNo = agrArray[RowAgrIndex]['agreementid'];
                    agrNo = selector.find('td:eq(1)').text();
                    $("#inputAgrNo").val(agrNo);

                    // agrDate = new Date(agrArray[RowAgrIndex]['agreedate']);
                    agrDate = new Date(selector.find('td:eq(2)').text());
                    $("#agrdatepicker").val(agrDate.toLocaleDateString());

                    selectCategoryDom = "";
                    while (i < apptypesArray.length) {
                        // selectCategoryDom += "<option style='background-color: #";//><div style='background-color: hsl(0.15turn, 50%, 75%);'><code>hsl(0.15turn, 50%, 75%)</code></div><div style='background-color: hsl(0.15turn, 50%, 75%);'>&nbsp;</div>";
                        // selectCategoryDom += apptypesArray[i]['color'].toString(16);
                        // selectCategoryDom += "'>";
                        selectCategoryDom += "<option value = '"
                        selectCategoryDom += i;
                        selectCategoryDom += "'";
                        if(selector.find('td:eq(3)').text()==apptypesArray[i]['Descr']){
                            selectCategoryDom += " selected ";
                        }
                        selectCategoryDom +=">";
                        selectCategoryDom += apptypesArray[i]['Descr'];
                        selectCategoryDom += "</option>";
                        i++;
                    }
                    $( "#selectAgrCategory" ).empty().append(selectCategoryDom);

                    agrTherapist = agrArray[RowAgrIndex]['agrtherapist'];
                    // agrTherapist = selector.find('td:eq(4)').text();
                    $("#inputAgrTherapist").val(agrTherapist);

                    // agrArea = agrArray[RowAgrIndex]['areas'];
                    agrArea = selector.find('td:eq(4)').text();
                    $("#inputAgrArea").val(agrArea);
                    
                    // agrNote = agrArray[RowAgrIndex]['agrnotes'];
                    agrNote = selector.find('td:eq(5)').text();
                    $("#textAgrNote").val(agrNote);
                    
                    // agrTotalamount = agrArray[RowAgrIndex]['agrprice'];
                    agrTotalamount = selector.find('td:eq(6)').text();
                    $("#inputAgrTotalamount").val(agrTotalamount);

                    if(agrArray[RowAgrIndex]['lastuser'] != usersArray.length){
                        agrLastuserupdated = "";//"null,am" + agrArray[RowAgrIndex]['lastupdate'];
                    } 
                    // else {
                    //     agrLastuserupdated = usersArray[agrArray[RowAgrIndex]['lastuser']]['name'] + ",am" + agrArray[RowAgrIndex]['lastupdate'];
                    // }
                    
                    $("#lblAgrupdated").text(agrLastuserupdated);


                    $('#modalAgreement').modal('show');
                });

                $('#details_appointments td').dblclick(function(){

                    RowAppIndex = $(this).parent().index();
                    selector = $(this).closest('tr');
                    showAppointment(selector);
                    $('#modalAppointment').modal('show');

                });
                function showAppointment() {
                    var i = 0;
                    appID = appArray[RowAppIndex]['id'];
                    appointmentId = appArray[RowAppIndex]['appointid'];
                    var agreedate = "";
                    selectAgreementDom = "";
                    while(i < agrArray.length){
                        selectAgreementDom += "<option value = '";
                        selectAgreementDom += i;
                        selectAgreementDom += "'";
                        if(agrArray[i]['agreementid'] == selector.find('td:eq(4)').text()){
                            selectAgreementDom += " selected ";
                        }
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
                    $( "#selectAgreement" ).empty().append(selectAgreementDom);

                    // starttime = new Date(appArray[RowAppIndex]['starttime']);
                    starttime = new Date(selector.find('td:eq(1)').text());
                    endtime = new Date(appArray[RowAppIndex]['endtime']);
                    $("#datepicker").val(starttime.toLocaleDateString());
                    $("#starttimepicker").val(starttime.getHours()+":"+starttime.getMinutes());
                    $("#endtimepicker").val(endtime.getHours()+":"+endtime.getMinutes());
                    
                    selectCategoryDom = "";
                    while (i < apptypesArray.length) {
                        // selectCategoryDom += "<option style='background-color: #";//><div style='background-color: hsl(0.15turn, 50%, 75%);'><code>hsl(0.15turn, 50%, 75%)</code></div><div style='background-color: hsl(0.15turn, 50%, 75%);'>&nbsp;</div>";
                        // selectCategoryDom += apptypesArray[i]['color'].toString(16);
                        // selectCategoryDom += "'>";
                        selectCategoryDom += "<option value = '"
                        selectCategoryDom += i;
                        selectCategoryDom += "'";
                        if(apptypesArray[RowAppIndex]['Descr']==apptypesArray[i]['Descr']){
                            selectCategoryDom += " selected ";
                        }
                        selectCategoryDom +=">";
                        selectCategoryDom += apptypesArray[i]['Descr'];
                        selectCategoryDom += "</option>";
                        i++;
                    }
                    $( "#selectCategory" ).empty().append(selectCategoryDom);

                    var i = 0;
                    
                    
                    while (i < appArray.length) {
                        selectTerapentDom += "<option>";
                        selectTerapentDom += appArray[i]['therapist'];
                        selectTerapentDom += "</option>";
                        i++;
                    }

                    $("#inputArea").val(selector.find('td:eq(5)').text());

                    // therapist = appArray[RowAppIndex]['therapist'];
                    therapist = selector.find('td:eq(2)').text();
                    $("#selectTherapist").val(therapist);
                    
                    textNote = appArray[RowAppIndex]['notes'];
                    textNote = selector.find('td:eq(3)').text();
                    $("#textNote").val(textNote);

                    sendsms = appArray[RowAppIndex]['sendsms'];
                    if(sendsms == 1){
                        $('#checksms').prop('checked', true);
                    }
                    
                    nopayment = appArray[RowAppIndex]['nopayment'];
                    if(nopayment == 1){
                        $('#checknopayment').prop('checked', true);
                    }
                    console.log(appArray[RowAppIndex]['lastuser']);
                    if(appArray[RowAppIndex]['lastuser'] != usersArray.length){
                        lastuserupdated = "null,am" + appArray[RowAppIndex]['lastupdate'];
                    } 
                    // else {
                    //     lastuserupdated = usersArray[appArray[RowAppIndex]['lastuser']]['name'] + ",am" + appArray[RowAppIndex]['lastupdate'];
                    // }
                    
                    $("#lblupdated").text(lastuserupdated);

                    var treatmentArray = selector.find('td:eq(5)').text().split("  ");
                    var secondgroup = [];
                    var treatmentsArray = [];
                    var treatmentDOM = "";
                    i = 0;
                    if(treatmentArray.length != 1 ) {
                        var grouparray = groupArr(treatmentArray, 2);
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
                    } else {
                        var arrays = appArray[RowAppIndex]['treatments'].split(" ");
                        while(i < arrays.length){
                            treatmentsArray[i] = {
                                                "treatment" : arrays[i],
                                                "ms" : 0,
                                                "joudle" : 0,
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
                    }
                    
                    
                    $('#tbltreatment > tbody:last-child').empty().append(treatmentDOM);

                    // inputTotalamount = appArray[RowAppIndex]['price'];
                    inputTotalamount = selector.find('td:eq(6)').text();
                    $("#inputTotalamount").val(inputTotalamount);

                    // inputPaidamount = appArray[RowAppIndex]['paidamount'];
                    inputPaidamount = selector.find('td:eq(7)').text();
                    $("#inputPaidamount").val(inputPaidamount);

                    if(appArray[RowAppIndex]['paymethod'] == 0) {
                        $('#radioBar').prop( 'checked', true );
                    } else {
                        $('#radioEC').prop( 'checked', true );
                    }
                }

                function showDelAppointment() {
                    var i = 0;
                    selectDelTypeDom = "<option value ='-1'> </optoin>";
                    while(i < delTypesArray.length){
                        selectDelTypeDom += "<option value = '";
                        selectDelTypeDom += i;
                        selectDelTypeDom += "'";
                        // if(delTypesArray[i]['agreementid'] == selector.find('td:eq(4)').text()){
                        //     selectDelTypeDom += " selected ";
                        // }
                        selectDelTypeDom +=">";
                        selectDelTypeDom += delTypesArray[i]['Descr'];
                        selectDelTypeDom += "</option>";
                        i++;
                    }
                    $( "#selectDelTypes" ).empty().append(selectDelTypeDom);
                }
                $('#deleteAppointmentbtn').click(function(e){
                    appdeltype = $("#selectDelTypes").val();
                    appID = appArray[RowAppIndex]['id'];
                    RowAppIndex +=1;
                    $.ajax({
                        type : "POST",
                        url : "{{route('backend.updateAppointment')}}",
                        data : {
                            type:"delete",
                            id:appID,
                            deletiontype : appdeltype,
                        },
                        success:function (response) {
                            var msgType = response.msgType;
                            var msg = response.msg;
                            $('#modalDelAppointment').modal('hide');
                            if (msgType == "success") {
                                alert(msg);
                                ReplaceRowColor(appTable,RowAppIndex,'red');
                            } else {
                                alert(msg);
                            }

                        }
                    });
                });
                $('#selectDelTypes').change(function() {
                    if ($(this).val() != '-1') {
                        // Do something for option "b"
                        $('#deleteAppointmentbtn').prop('disabled', false);
                    } else {
                        $('#deleteAppointmentbtn').prop('disabled', true);
                    }
                });

                $("#updateAgreementbtn").click(function(e){
                    e.preventDefault();
                    agrNo = $("#inputAgrNo").val();
                    
                    RowAgrIndex += 1;
                    agrDate = $("#agrdatepicker").val();
                    ReplaceCellContent(agrTable,RowAgrIndex,2,agrDate);

                    apptype = $("#selectAgrCategory").val(); 
                    apptype = apptypesArray[apptype]['Descr'];
                    ReplaceCellContent(agrTable,RowAgrIndex,3,apptype);

                    agrTherapist = $("#inputAgrTherapist").val();
                    // ReplaceCellContent(table,RowAgrIndex,4,agrTherapist);

                    agrArea = $("#inputAgrArea").val();
                    ReplaceCellContent(agrTable,RowAgrIndex,4,agrArea);

                    agrNote = $("#textAgrNote").val();
                    ReplaceCellContent(agrTable,RowAgrIndex,5,agrNote);

                    var agrTotalamountori = agrTotalamount;
                    agrTotalamount = $("#inputAgrTotalamount").val();
                    ReplaceCellContent(agrTable,RowAgrIndex,6,agrTotalamount);

                    agrLastuserupdated = "";

                    $.ajax({
                        type : "POST",
                        url : "{{route('backend.updateAgreement')}}",
                        data : {
                            id : agrID,
                            agreementid:agrNo,
                            agreedate:agrDate,
                            category : apptype,
                            areas : agrArea,
                            therapist : agrTherapist,
                            notes : agrNote,
                            price : agrTotalamount,
                        },
                        success:function (response) {
                            var msgType = response.msgType;
                            var msg = response.msg;
                            $('#modalAgreement').modal('hide');
                            if (msgType == "success") {
                                alert(msg);
                                
                            } else {
                                alert(msg);
                            }

                        }
                    });

                });

                $("#updateAppointmentbtn").click(function(e){
                    e.preventDefault();
                    console.log("AppID",appID);
                    RowAppIndex += 1;
                    agreementid = $("#selectAgreement").val(); 
                    agreementid = agrArray[agreementid]['agreementid'];
                    ReplaceCellContent(appTable,RowAppIndex,4,agreementid);

                    var getendtime = $("#datepicker").val() + " "+$('#endtimepicker').val();
                    endtime = getendtime + ":00";

                    var getstarttime = $("#datepicker").val() + " "+$('#starttimepicker').val();
                    starttime = getstarttime + ":00";
                    var starttime1 = new Date(starttime);
                    var dateStringWithTime = moment(starttime1).format('MM.DD.YYYY HH:mm:ss');

                    ReplaceCellContent(appTable,RowAppIndex,1,dateStringWithTime);

                    therapist = $("#selectTherapist").val();
                    ReplaceCellContent(appTable,RowAppIndex,2,therapist);

                    textNote =  $("#textNote").val();
                    ReplaceCellContent(appTable,RowAppIndex,3,textNote);

                    inputTotalamount = $("#inputTotalamount").val();
                    ReplaceCellContent(appTable,RowAppIndex,6,inputTotalamount);

                    inputPaidamount = $("#inputPaidamount").val();
                    ReplaceCellContent(appTable,RowAppIndex,7,inputPaidamount);

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
                    ReplaceCellContent(appTable,RowAppIndex,5,treatments);

                    $.ajax({
                        type : 'POST',
                        url: base_url + '/backend/updateAppointment',
                        data: {
                            type:"update",
                            id : appID,
                            appointmentId:appointmentId,
                            agreementid:agreementid,
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
                            $('#modalAppointment').modal('hide');
                            if (msgType == "success") {
                                alert(msg);
                                
                            } else {
                                alert(msg);
                            }
                        }
                    });
                });
                
                $(".cancelbtn").click(function(){
                    $('#modalAppointment').modal('hide');
                    $('#modalAgreement').modal('hide');
                    $('#modalDelAppointment').modal('hide');
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
        $('#agrdatepicker').datepicker({
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
@endpush
