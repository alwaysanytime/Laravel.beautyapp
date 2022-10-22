@extends('layouts.backend')

@section('content')

    <?php
    //    app('App/Http/Controllers/Backend/CalendarController.php')->showAppointments();

    ?>
    @push('style')
        <link href='{{asset("public/assets/fullcalendar/lib/main.css")}}' rel='stylesheet'/>
    @endpush

    <div class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="dash-heading mb-10">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 style="padding-left: 15px">Kalender</h2>
                            </div>
                        </div>
                    </div>
                    <div id="calendar"></div>
                    <!--                    --><?//= dechex(9430514)?>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src='{{asset("public/assets/fullcalendar/lib/main.js")}}'></script>
    <script src='{{asset("public/assets/fullcalendar/lib/sweetalert.min.js")}}'></script>
    <script src='{{asset("public/assets/fullcalendar/lib/locales/de.js")}}'></script>
    
    <script type="text/javascript">
        var passedArray = <?php echo json_encode($data);?>


        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: "de",
                firstDay: 1,
                slotMinTime: "08:00:00",
                slotMaxTime: "23:00:00",
                timeFormat: 'H(:mm)',
                contentHeight: 'auto',
                editable: true,
                navLinks: true,
                allDaySlot: false,
                eventClick: function (calEvent, jsEvent, view, resourceObj) {

                    calEvent.jsEvent.preventDefault();

                    swal({
                        text: calEvent.event.start.toLocaleString("de-DE"),
                        title: calEvent.event.title,
                        buttons: {
                            cancel: "Abbrechen",
                            details: {
                                text: "Bearbeiten",
                                value: "details",
                            },
                        },
                    })
                        .then((value) => {

                            switch (value) {

                                case "details":
                                    if (calEvent.event.url) {
                                        let array = calEvent.event.url.split(" ");
                                        var agrId = array[0];
                                        var custId = array[1];
                                        var form = $('<form action="{{route('backend.calendarDetails')}}" method="post">' +
                                            '@csrf' +
                                            '<input type="text" name="agreement_id" value="' + agrId + '"/>' +
                                            '<input type="text" name="customer_id" value="' + custId + '"/>' +
                                            '</form>');
                                        $('body').append(form);
                                        form.submit();
                                    }
                                    break;

                                default:
                                    break;
                            }
                        });
                }
            });

            for (var i = 0; i < passedArray.length; i++) {

                var hexcolor = passedArray[i]["color"] == null ? "" : passedArray[i]["color"].toString(16).match(/.{1,2}/g);
                var hexfontcolor = passedArray[i]["fontcolor"] == null ? "" : passedArray[i]["fontcolor"].toString(16).match(/.{1,2}/g);

                calendar.addEvent({
                    title: passedArray[i]["subject"] == null ? (passedArray[i]["notes"] == null ? "" : passedArray[i]["notes"]) : passedArray[i]["subject"] + " - " + passedArray[i]["notes"],
                    start: passedArray[i]["starttime"],
                    end: passedArray[i]["endtime"],
                    color: ('#' + hexcolor[2] + hexcolor[1] + hexcolor[0]),
                    textColor: ('#' + hexfontcolor[2] + hexfontcolor[1] + hexfontcolor[0]),
                    allDay: false,
                    url: passedArray[i]["agreementid"] + " " + passedArray[i]["customerid"]
                });

            }


            calendar.render();
        });
    </script>
@endpush
