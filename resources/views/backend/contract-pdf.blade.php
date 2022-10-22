@extends('layouts.backend')

@section('content')

    <div class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="tw-card">
                        <div class="row">
                            <div class="col-md-5" style="display: inline">
                                <h4>Vereinbarung als PDF</h4>
                            </div>
{{--                            <div class="col-md-5" style="display: inline">--}}
{{--                                <h2>PDF</h2>--}}
{{--                            </div>--}}
                        </div>
{{--                        <input class="upload_img" formtarget="_blank" onclick="window.location='{{route("backend.contract-pdf")}}'" id="download_pdf" type='button' name='download' value='Vereinbarung öffnen'>--}}
                        <a class="upload_img" target="_blank" href="{{route("backend.contract-pdf",["pdfclient" => $pdf_file_local[0], "pdffile" => $pdf_file_local[1]])}}" id="download_pdf" type='button' name='download'>Vereinbarung öffnen</a>
                    </div>
                </div>
            </div>

            <div id="alert_div">
                <?php if (isset($msg["error"])): ?>
                <div class="screenAlert-icon screenAlert-error animate">
                    <span class="screenAlert-x-mark">
                    <span class="screenAlert-line screenAlert-left animateXLeft"></span>
                    <span class="screenAlert-line screenAlert-right animateXRight"></span>
                    </span>
                    <div class="screenAlert-placeholder"></div>
                    <div class="screenAlert-fix"></div>
                </div>
                <h5 class="mb-lg-3" style="text-align: center"><?= $msg["error"]; ?></h5>
                <?php endif; ?>
                <?php if (isset($msg["success"])): ?>
                <div class="screenAlert-icon screenAlert-success animate">
                    <span class="screenAlert-line screenAlert-tip animateSuccessTip"></span>
                    <span class="screenAlert-line screenAlert-long animateSuccessLong"></span>
                    <div class="screenAlert-placeholder"></div>
                    <div class="screenAlert-fix"></div>
                </div>
                <h5 class="mb-lg-3" style="text-align: center"><?= $msg["success"]; ?></h5>
                <?php endif; ?>
            </div>
        </div>
    </div>

@endsection

@push("scripts")
    <script type="text/javascript">
        $(document).ready(function () {
            var target = $("#alert_div");
            setTimeout(function () {
                target.animate({
                    opacity: "-=1"
                }, 900, function () {
                    target.remove();
                });
            }, 1000);
        });
    </script>
@endpush


