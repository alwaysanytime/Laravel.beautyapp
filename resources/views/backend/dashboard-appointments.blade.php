@extends('layouts.backend')

@section('content')

    @push('style')
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('public/assets/css/dashboard-appointment.css')}}">
        <link media="all" type="text/css" rel="stylesheet" href="{{asset('public/assets/datatables/dataTables.bootstrap4.min.css')}}">
    @endpush

    <div id="table_close_on_contract" class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="dash-heading mb-10">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 style="padding-left: 15px">Behandlungen</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mx-auto">
                    <div class="card rounded shadow border-0">
                        <div class="card-body p-5 bg-white rounded">
                            <div class="table-responsive">
                                <table id="appointtoday_table" style="width:100%" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Uhrzeit</th>
                                        <th>Kunde</th>
                                        <th>Notiz</th>
                                        <th>Anwendungen</th>
                                        <th>SMS</th>
                                        <th>Preis</th>
                                        <th>Bezahlt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data as $appointment):?>
                                    <tr>
                                        <td><?=gmdate("H:i", strtotime($appointment->starttime))?></td>
                                        <td><?=$appointment->subject?></td>
                                        <td><?=$appointment->notes?></td>
                                        <td><?=$appointment->treatments?></td>
                                        <td style="float: center"><img src="/public/assets/images/{{ $appointment->smssent }}.png"></td>
                                        <td style="text-align: right"><?=$appointment->price?></td>
                                        <td style="text-align: right"><?=$appointment->paidamount?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
						                <br>
														<div class="col-lg-3" style="float: right">
						                    <table id="appointmentPayment" style="width:100%" class="table table-striped table-bordered table-hover">
						                        <tr>
						                          <td>Gesamtbetrag</td>
						                          <td style="text-align: right"><?=number_format($data->cashAmount+$data->cardAmount, 2) ?></td>
						                        </tr>
						                        <tr>
						                          <td>Bar-Zahlung</td>
						                          <td style="text-align: right"><?=number_format($data->cashAmount, 2) ?></td>
						                        </tr>
						                        <tr>
						                          <td>ES-Zahlung</td>
						                          <td style="text-align: right"><?=number_format($data->cardAmount,2) ?></td>
						                        </tr>
						                        <tr>
						                          <td>Ein-/Auszahlung</td>
						                          <td style="text-align: right"><?=number_format($data->paymentAmount, 2) ?></td>
						                        </tr>
						                        <tr>
						                          <td>Tageseinnahmen</td>
						                          <td style="text-align: right"><?=number_format($data->cashAmount+$data->cardAmount+$data->paymentAmount, 2) ?></td>
						                        </tr>
						                    </table>
						                </div>
	  			            		</div>
	  			            </div>
									</div>
	            </div>
           </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('public/assets/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('public/assets/datatables/dataTables.bootstrap4.min.js')}}"></script>

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

                var appArray = <?php echo json_encode($data);?>;

                // $('#appointmentPayment').DataTable({
                //     "pageLength": 25,
                //     "order": [[ 1, "asc" ]],
                //     "language": {
                //         "sEmptyTable": "Keine Daten in der Tabelle vorhanden",
                //         "sInfo": "_START_ bis _END_ von _TOTAL_ Einträgen",
                //         "sInfoEmpty": "0 bis 0 von 0 Einträgen",
                //         "sInfoFiltered": "(gefiltert von _MAX_ Einträgen)",
                //         "sInfoPostFix": "",
                //         "sInfoThousands": ".",
                //         "sLengthMenu": "Einträge anzeigen _MENU_",
                //         "sLoadingRecords": "Wird geladen...",
                //         "sProcessing": "Bitte warten...",
                //         "sSearch": "Suchen",
                //         "sZeroRecords": "Keine Einträge vorhanden.",
                //         "oPaginate": {
                //             "sFirst": "Erste",
                //             "sPrevious": "Zurück",
                //             "sNext": "Nächste",
                //             "sLast": "Letzte"
                //         },
                //         "oAria": {
                //             "sSortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                //             "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                //         }
                //     }
                // });
                $('#appointtoday_table td').dblclick(function(e){
                    e.preventDefault();
                    var RowAgrIndex = $(this).parent().index();
                    var customer_id = appArray[RowAgrIndex]['customerid'];
                    var agreement_id = appArray[RowAgrIndex]['agreementid'];
                    console.log(RowAgrIndex,customer_id,agreement_id);

                    var form = $('<form action="{{route('backend.calendarDetails')}}" method="post">' +
                        '@csrf' +
                        '<input type="text" name="agreement_id" value="' + agreement_id + '"/>' +
                        '<input type="text" name="customer_id" value="' + customer_id + '"/>' +
                        '</form>');
                    $('body').append(form);
                    form.submit();

                    //  $.ajax({
                    //     type : 'POST',
                    //     url: "{{route('backend.calendarDetails')}}",
                    //     data: {
                    //         customerid:customer_id,
                    //         agreementid:agreement_id,
                    //     },
                    //     success: function (response) {
                    //         // var msg = response.msg;
                    //         // if (msgType == "success") {
                    //         //     alert(msg);
                                
                    //         // } else {
                    //         //     alert(msg);
                    //         // }
                    //     }
                    // });


                });


            });
        });
    </script>
    
@endpush
