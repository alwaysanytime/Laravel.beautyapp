@extends('layouts.backend')

@section('content')

    <?php
    if (session()->exists('msg') && session()->exists("_old_input")) {
        $selected_customer_id = session()->pull("_old_input");
        $msg = session()->pull("msg");
    }
    if (isset($_GET["id"])) {
        $on_upload_customer_id = $_GET["id"];
    }

    $customer_info = "";
    foreach ($data as $users) {
        if (isset($_GET["id"])) {
            if ($_GET["id"] == $users->id)
                $customer_info = $users->firstname . " " . $users->lastname;
        }
    }

    if (session()->exists("client_infos")) {
        $client_uploaded_infos = session()->pull("client_infos");
    }

    ?>

    <div class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="be-heading mb-10">
                        <div class="row">
                            <div class="col-md-4 mb-10">
                                <h2>{{ __('Photos') }}</h2>
                            </div>
                            <div style="margin-left: auto; margin-right: 0; padding-top: 15px; padding-right: 15px;">
                                <h6 style="font-style: oblique"><?=$customer_info?></h6>
                            </div>

                        </div>
                    </div>
                    <form id="form_upload_img" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{--                            <div class="col-md-5">--}}
                            {{--                                <div class="search" style="width: 100%">--}}
                            {{--                                    <select id="search_txt" class="chosen-select" name="selected_customer"--}}
                            {{--                                            id="upload_select"--}}
                            {{--                                            style="width: 100%;">--}}
                            {{--                                        <option disabled selected>-- Musteri --</option>--}}
                            {{--                                        @foreach($data as $user)--}}
                            {{--                                            <option--}}
                            {{--                                                @if(isset($selected_customer_id["selected_customer"]))--}}
                            {{--                                                @if($selected_customer_id["selected_customer"] == $user->id)--}}
                            {{--                                                <?="selected"?>--}}
                            {{--                                                @endif--}}
                            {{--                                                @elseif(isset($on_upload_customer_id))--}}
                            {{--                                                @if($on_upload_customer_id == $user->id)--}}
                            {{--                                                <?="selected"?>--}}
                            {{--                                                @endif--}}
                            {{--                                                @else--}}
                            {{--                                                <?=null?>--}}
                            {{--                                                @endif--}}
                            {{--                                                value="{{$user->id}}"--}}
                            {{--                                                class="option">{{$user->firstname}} {{$user->lastname}}</option>--}}
                            {{--                                        @endforeach--}}
                            {{--                                    </select>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="col-md-12">

                                <input class="select_img" id="select_img" {{--onchange="uploadUserProfileImage()"--}} type="file" name="file[]" multiple/>

                            </div>
                        </div>
                    </form>
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

            {{--TABLE--}}
            <?php if(isset($client_uploaded_infos)){?>
            <table class="container_mytable" id="img_table" border="3" align="center">
                <tbody>
                <?php $img_count = 0;?>
                <?php foreach ($client_uploaded_infos as $client_uploaded_info):?>
                <tr id="img_table_rows" style="text-align: center">
                    <td id="third_td">
                    		<img id="myImg[<?=$img_count?>]" style="cursor: zoom-in" border=3 height=120 width=160 src="data:image/jpeg;base64,<?=base64_encode($client_uploaded_info["thumb"])?>"/>
                    </td>
                    <td id="last_td">
                    		<a class="btn btn-sm btn-danger" href="{{ route("backend.FileDeleted", ['id' => $client_uploaded_info["id"], "customer_id" => $client_uploaded_info["customerid"]]) }}">Löschen</a>
                    </td>
                </tr>
                <?php if ($img_count < count($client_uploaded_infos)) $img_count++;?>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php }?>
            {{--*************--}}

            {{--IMAGE CLICK SHOW BIGGER--}}
            <div id="myModal" class="modal_img">
                <img class="modal-content_img" id="img01">
            </div>
            {{--*************--}}

        </div>
    </div>

    <!--    --><?php
    //    define("PATH", realpath("."));
    //    if (file_exists(PATH.'/downloads/clientimages/test2')){
    //            echo "DOSYA ZATEN MEVCUT!";
    //    }else
    //        {
    //        mkdir(PATH.'/downloads/clientimages/140220',0777);
    //        }
    //    ?>

    {{--            <div class="col-md-4 mb-10">--}}
    {{--                <div class="search">--}}
    {{--                    <form id="form_upload_img" method="POST" action="" enctype="multipart/form-data">--}}
    {{--                        <input type="file" name="file"/>--}}
    {{--                        <div>--}}
    {{--                            <input id="upload_submit" type='submit' name='submit' value='Upload'>--}}
    {{--                        </div>--}}
    {{--                        <select name="selected_customer" id="upload_select" style="width: 100%">--}}
    {{--                            <option disabled selected>-- Musteri --</option>--}}

    {{--                            <option value="1" class="option">asdsad</option>--}}

    {{--                        </select>--}}
    {{--                    </form>--}}
    {{--                </div>--}}
    {{--            </div>--}}


    {{--            <div id="upload_content">--}}
    {{--                <?php if (isset($error)): ?>--}}
    {{--                <h3 class="mb-lg-3" style="padding-top: 10px; padding-left: 225px"><?= $error; ?></h3>--}}
    {{--                <?php endif; ?>--}}
    {{--                <?php if (isset($success)): ?>--}}
    {{--                <h3 class="mb-lg-3" style="padding-top: 10px; padding-left: 225px"><?= $success; ?></h3>--}}
    {{--                <?php endif; ?>--}}
    {{--                <form id="form_upload_img" method="POST" action="" enctype="multipart/form-data">--}}
    {{--                    <input type="file" name="file"/> <!--  file[] multiple -- coklu foto secimi     -->--}}
    {{--                    <div>--}}
    {{--                        <input id="upload_submit" type='submit' name='submit' value='Upload'>--}}
    {{--                    </div>--}}
    {{--                    <select name="selected_customer" id="upload_select" style="width: 100%">--}}
    {{--                        <option disabled selected>-- Musteri --</option>--}}
    {{--                        <?php foreach ($get_allCustomers as $customer): ?>--}}
    {{--                        <option <?= ($_POST["selected_customer"] == $customer["customer_id"]) ? "selected" : null ?>--}}
    {{--                                value="<?= $customer["customer_id"] ?>"--}}
    {{--                                class="option"><?= $customer["customer_name"] ?></option>--}}
    {{--                        <?php endforeach; ?>--}}
    {{--                    </select>--}}
    {{--                </form>--}}
    {{--            </div>--}}

    {{--            <?php foreach ($row as $item): ?>--}}
    {{--            <div class="row" style="display: flex; flex-wrap: wrap; padding: 0 4px">--}}
    {{--                <div class="column" style="flex: 25%; max-width: 25%; padding: 0 4px">--}}
    {{--                    <img id="img_form" style="margin-top: 8px; vertical-align: middle; width: 100%"--}}
    {{--                         src="app/Clientimages/<?= $item["file_name"] ?>" alt=""/>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        <?php endforeach; ?>--}}

@endsection

@push('scripts')
    <!-- client js -->
    <script src="{{asset('public/pages/client.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var target = $("#alert_div");
            setTimeout(function () {
                target.animate({
                    opacity: "-=1"
                }, 500, function () {
                    target.remove();
                });
            }, 800);

            setTimeout(function () {
                $("#img_table").toggleClass("show", 750);
            }, 1550);

					  $("#select_img").on("change", function(){
					    $("#form_upload_img").submit();
					  });

        });
    </script>
    <script>
        $('#img_table_rows #third_td img').click(function (event) {
            var element = $(event.target);
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("img01");

            modal.style.cursor = "zoom-out";
            modal.style.display = "block";
            modalImg.src = element[0].src;

            modal.onclick = function () {
                modal.style.display = "none";
            }

        });
    </script>
    
@endpush
