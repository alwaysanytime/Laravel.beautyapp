@extends('layouts.backend')

@section('content')

    @push('style')
        <link media="all" type="text/css" rel="stylesheet" href="{{asset("public/assets/css/brand.css")}}">
        <link media="all" type="text/css" rel="stylesheet"
              href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @endpush

    <?php
    if (session()->exists('msg') && session()->exists("_old_input")) {
        $selected_customer_id = session()->pull("_old_input");
        $msg = session()->pull("msg");
    }

    $customer_fullname = "";
    $customer_info = "";
    foreach ($data as $users) {
        if (isset($_GET["id"])) {
            if ($_GET["id"] == $users->id) {
                $customer_fullname = $users->firstname . " " . $users->lastname;
                $customer_info = $users;
            }
        }
    }
    ?>

    <?php if(isset($_GET["id"])): ?>
    <div id="main_close_on_contract" class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="be-heading mb-10">
                        <div class="row">
                            <div class="col-md-4 mb-10">
                                <h2>{{ __('Vereinbarung') }}</h2>
                            </div>
                            <div style="margin-left: auto; margin-right: 0; padding-top: 15px; padding-right: 15px;">
                                <h6 style="font-style: oblique"><?=$customer_fullname?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="tw-card">
                                <div id="app" old="[]">

                                    <div class="container text-center">
                                        <img src="/public/media/medical_hairless_esthetic 300.png" alt="" width="300">
                                        <hr>

                                        <h1>Behandlungsvertrag</h1>
                                        <h4>Laserhaarentfernung</h4>

                                        <hr>
                                    </div>

                                    <div class="container text-center">
                                        <h5>zwischen</h5>

                                        <p class="lead">
                                            Medical Hairless & Esthetic<br>
                                            Bahnhofstr. 1<br>59065 Hamm<br>
                                        </p>

                                        <p class="text-muted">(Behandlungsinstitut)</p>

                                        <h5>und</h5>

                                        <p class="lead">
                                            <?=$customer_fullname?><br>
                                            <?=$customer_info->address1?>
                                            <br><?=$customer_info->zipcode?> <?=$customer_info->city?><br>
                                        </p>
                                        <p class="text-muted">(Kunde)</p>
                                        <hr>
                                        <p>
                                            wird ein Behandlungsvertrag mit den folgenden genannten Arealen zu
                                            nachstehenden
                                            Konditionen
                                            vereinbart: </p>
                                    </div>

                                    <form method="POST"
                                          action=""
                                          accept-charset="UTF-8" onsubmit="return checkBeforeSubmit();"><input name="_token"
                                                                        type="hidden" {{--value="Mdg2MucdfDesw9Y1L0LA06hBOGNMCGmzQwWDY2vN"--}}>
                                        @csrf
                                        <div class="container">
                                            <div class="notification">

                                            </div>

                                            <input id="sex" name="sex" type="hidden" value="2">

                                            <services
                                                :data="[
																											   {
																											      &quot;id&quot;:1,
																											      &quot;sort&quot;:1,
																											      &quot;name&quot;:&quot;Kopf&quot;,
																											      &quot;created_at&quot;:&quot;-000001-11-29T23:06:32.000000Z&quot;,
																											      &quot;updated_at&quot;:&quot;2018-11-20T10:04:40.000000Z&quot;,
																											      &quot;services&quot;:[
																											         {
																											            &quot;id&quot;:35,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Gesicht&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;59.00&quot;,
																											            &quot;price_female2&quot;:&quot;49.00&quot;,
																											            &quot;price_female3&quot;:&quot;39.00&quot;,
																											            &quot;price_male_try&quot;:&quot;120.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;110.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;100.00&quot;,
																											            &quot;price_female_try&quot;:&quot;120.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;110.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;100.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;59.00&quot;,
																											            &quot;female2_price&quot;:&quot;49.00&quot;,
																											            &quot;female3_price&quot;:&quot;39.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:36,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Oberlippe&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;29.00&quot;,
																											            &quot;price_female2&quot;:&quot;24.00&quot;,
																											            &quot;price_female3&quot;:&quot;19.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;20.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;29.00&quot;,
																											            &quot;female2_price&quot;:&quot;24.00&quot;,
																											            &quot;female3_price&quot;:&quot;19.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:77,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Kinn&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;29.00&quot;,
																											            &quot;price_female2&quot;:&quot;24.00&quot;,
																											            &quot;price_female3&quot;:&quot;19.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;20.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;29.00&quot;,
																											            &quot;female2_price&quot;:&quot;24.00&quot;,
																											            &quot;female3_price&quot;:&quot;19.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:38,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Hals&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;49.00&quot;,
																											            &quot;price_male2&quot;:&quot;39.00&quot;,
																											            &quot;price_male3&quot;:&quot;29.00&quot;,
																											            &quot;price_female&quot;:&quot;39.00&quot;,
																											            &quot;price_female2&quot;:&quot;29.00&quot;,
																											            &quot;price_female3&quot;:&quot;19.00&quot;,
																											            &quot;price_male_try&quot;:&quot;50.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;20.00&quot;,
																											            &quot;price_female_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;30.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;49.00&quot;,
																											            &quot;male2_price&quot;:&quot;39.00&quot;,
																											            &quot;male3_price&quot;:&quot;29.00&quot;,
																											            &quot;female_price&quot;:&quot;39.00&quot;,
																											            &quot;female2_price&quot;:&quot;29.00&quot;,
																											            &quot;female3_price&quot;:&quot;19.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:78,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Nacken&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;49.00&quot;,
																											            &quot;price_male2&quot;:&quot;39.00&quot;,
																											            &quot;price_male3&quot;:&quot;29.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;60.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;50.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;45.00&quot;,
																											            &quot;price_female_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;30.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;49.00&quot;,
																											            &quot;male2_price&quot;:&quot;39.00&quot;,
																											            &quot;male3_price&quot;:&quot;29.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:79,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Koteletten&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;29.00&quot;,
																											            &quot;price_female2&quot;:&quot;24.00&quot;,
																											            &quot;price_female3&quot;:&quot;19.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;0.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;29.00&quot;,
																											            &quot;female2_price&quot;:&quot;24.00&quot;,
																											            &quot;female3_price&quot;:&quot;19.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:80,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Nasenl\u00f6cher&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;25.00&quot;,
																											            &quot;price_male2&quot;:&quot;20.00&quot;,
																											            &quot;price_male3&quot;:&quot;15.00&quot;,
																											            &quot;price_female&quot;:&quot;25.00&quot;,
																											            &quot;price_female2&quot;:&quot;20.00&quot;,
																											            &quot;price_female3&quot;:&quot;15.00&quot;,
																											            &quot;price_male_try&quot;:&quot;10.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;10.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;10.00&quot;,
																											            &quot;price_female_try&quot;:&quot;20.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;15.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;10.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;25.00&quot;,
																											            &quot;male2_price&quot;:&quot;20.00&quot;,
																											            &quot;male3_price&quot;:&quot;15.00&quot;,
																											            &quot;female_price&quot;:&quot;25.00&quot;,
																											            &quot;female2_price&quot;:&quot;20.00&quot;,
																											            &quot;female3_price&quot;:&quot;15.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:65,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Augenbrauen&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;25.00&quot;,
																											            &quot;price_male2&quot;:&quot;20.00&quot;,
																											            &quot;price_male3&quot;:&quot;15.00&quot;,
																											            &quot;price_female&quot;:&quot;25.00&quot;,
																											            &quot;price_female2&quot;:&quot;20.00&quot;,
																											            &quot;price_female3&quot;:&quot;15.00&quot;,
																											            &quot;price_male_try&quot;:&quot;30.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;20.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;10.00&quot;,
																											            &quot;price_female_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;20.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;10.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;25.00&quot;,
																											            &quot;male2_price&quot;:&quot;20.00&quot;,
																											            &quot;male3_price&quot;:&quot;15.00&quot;,
																											            &quot;female_price&quot;:&quot;25.00&quot;,
																											            &quot;female2_price&quot;:&quot;20.00&quot;,
																											            &quot;female3_price&quot;:&quot;15.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:64,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Oberwangen&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;59.00&quot;,
																											            &quot;price_male2&quot;:&quot;49.00&quot;,
																											            &quot;price_male3&quot;:&quot;39.00&quot;,
																											            &quot;price_female&quot;:&quot;0.00&quot;,
																											            &quot;price_female2&quot;:&quot;0.00&quot;,
																											            &quot;price_female3&quot;:&quot;0.00&quot;,
																											            &quot;price_male_try&quot;:&quot;59.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;49.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;39.00&quot;,
																											            &quot;price_female_try&quot;:&quot;59.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;49.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;39.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;59.00&quot;,
																											            &quot;male2_price&quot;:&quot;49.00&quot;,
																											            &quot;male3_price&quot;:&quot;39.00&quot;,
																											            &quot;female_price&quot;:&quot;0.00&quot;,
																											            &quot;female2_price&quot;:&quot;0.00&quot;,
																											            &quot;female3_price&quot;:&quot;0.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:63,
																											            &quot;category_id&quot;:1,
																											            &quot;name&quot;:&quot;Ohren&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;29.00&quot;,
																											            &quot;price_male2&quot;:&quot;19.00&quot;,
																											            &quot;price_male3&quot;:&quot;9.00&quot;,
																											            &quot;price_female&quot;:&quot;0.00&quot;,
																											            &quot;price_female2&quot;:&quot;0.00&quot;,
																											            &quot;price_female3&quot;:&quot;0.00&quot;,
																											            &quot;price_male_try&quot;:&quot;9.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;19.00&quot;, 
																											            &quot;price_male3_try&quot;:&quot;29.00&quot;,
																											            &quot;price_female_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;0.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;29.00&quot;,
																											            &quot;male2_price&quot;:&quot;19.00&quot;,
																											            &quot;male3_price&quot;:&quot;9.00&quot;,
																											            &quot;female_price&quot;:&quot;0.00&quot;,
																											            &quot;female2_price&quot;:&quot;0.00&quot;,
																											            &quot;female3_price&quot;:&quot;0.00&quot;
																											         }
																											      ]
																											   },
																											   {
																											      &quot;id&quot;:2,
																											      &quot;sort&quot;:2,
																											      &quot;name&quot;:&quot;Oberk\u00f6rper&quot;,
																											      &quot;created_at&quot;:&quot;-000001-11-29T23:06:32.000000Z&quot;,
																											      &quot;updated_at&quot;:&quot;2018-11-20T10:04:40.000000Z&quot;,
																											      &quot;services&quot;:[
																											         {
																											            &quot;id&quot;:81,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Oberarme&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;99.00&quot;,
																											            &quot;price_male2&quot;:&quot;89.00&quot;,
																											            &quot;price_male3&quot;:&quot;79.00&quot;,
																											            &quot;price_female&quot;:&quot;69.00&quot;,
																											            &quot;price_female2&quot;:&quot;59.00&quot;,
																											            &quot;price_female3&quot;:&quot;49.00&quot;,
																											            &quot;price_male_try&quot;:&quot;120.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;110.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;100.00&quot;,
																											            &quot;price_female_try&quot;:&quot;100.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;90.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;80.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;99.00&quot;,
																											            &quot;male2_price&quot;:&quot;89.00&quot;,
																											            &quot;male3_price&quot;:&quot;79.00&quot;,
																											            &quot;female_price&quot;:&quot;69.00&quot;,
																											            &quot;female2_price&quot;:&quot;59.00&quot;,
																											            &quot;female3_price&quot;:&quot;49.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:41,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Unterarme&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;99.00&quot;,
																											            &quot;price_male2&quot;:&quot;89.00&quot;,
																											            &quot;price_male3&quot;:&quot;79.00&quot;,
																											            &quot;price_female&quot;:&quot;79.00&quot;,
																											            &quot;price_female2&quot;:&quot;69.00&quot;,
																											            &quot;price_female3&quot;:&quot;59.00&quot;,
																											            &quot;price_male_try&quot;:&quot;120.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;110.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;100.00&quot;,
																											            &quot;price_female_try&quot;:&quot;100.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;90.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;80.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;99.00&quot;,
																											            &quot;male2_price&quot;:&quot;89.00&quot;,
																											            &quot;male3_price&quot;:&quot;79.00&quot;,
																											            &quot;female_price&quot;:&quot;79.00&quot;,
																											            &quot;female2_price&quot;:&quot;69.00&quot;,
																											            &quot;female3_price&quot;:&quot;59.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:82,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;H\u00e4nde&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;29.00&quot;,
																											            &quot;price_male2&quot;:&quot;19.00&quot;,
																											            &quot;price_male3&quot;:&quot;15.00&quot;,
																											            &quot;price_female&quot;:&quot;29.00&quot;,
																											            &quot;price_female2&quot;:&quot;19.00&quot;,
																											            &quot;price_female3&quot;:&quot;15.00&quot;,
																											            &quot;price_male_try&quot;:&quot;50.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;30.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;29.00&quot;,
																											            &quot;male2_price&quot;:&quot;19.00&quot;,
																											            &quot;male3_price&quot;:&quot;15.00&quot;,
																											            &quot;female_price&quot;:&quot;29.00&quot;,
																											            &quot;female2_price&quot;:&quot;19.00&quot;,
																											            &quot;female3_price&quot;:&quot;15.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:43,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;R\u00fccken&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;209.00&quot;,
																											            &quot;price_male2&quot;:&quot;189.00&quot;,
																											            &quot;price_male3&quot;:&quot;139.00&quot;,
																											            &quot;price_female&quot;:&quot;109.00&quot;,
																											            &quot;price_female2&quot;:&quot;99.00&quot;,
																											            &quot;price_female3&quot;:&quot;89.00&quot;,
																											            &quot;price_male_try&quot;:&quot;250.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;230.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;210.00&quot;,
																											            &quot;price_female_try&quot;:&quot;200.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;180.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;170.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;209.00&quot;,
																											            &quot;male2_price&quot;:&quot;189.00&quot;,
																											            &quot;male3_price&quot;:&quot;139.00&quot;,
																											            &quot;female_price&quot;:&quot;109.00&quot;,
																											            &quot;female2_price&quot;:&quot;99.00&quot;,
																											            &quot;female3_price&quot;:&quot;89.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:44,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Stei\u00dfbein&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;49.00&quot;,
																											            &quot;price_male2&quot;:&quot;39.00&quot;,
																											            &quot;price_male3&quot;:&quot;29.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;100.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;90.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;80.00&quot;,
																											            &quot;price_female_try&quot;:&quot;80.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;70.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;60.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;49.00&quot;,
																											            &quot;male2_price&quot;:&quot;39.00&quot;,
																											            &quot;male3_price&quot;:&quot;29.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:100,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Dekollet\u00e9&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;30.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:46,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Brust&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;129.00&quot;,
																											            &quot;price_male2&quot;:&quot;99.00&quot;,
																											            &quot;price_male3&quot;:&quot;79.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;100.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;90.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;80.00&quot;,
																											            &quot;price_female_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;30.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;129.00&quot;,
																											            &quot;male2_price&quot;:&quot;99.00&quot;,
																											            &quot;male3_price&quot;:&quot;79.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:47,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Bauch&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;119.00&quot;,
																											            &quot;price_male2&quot;:&quot;99.00&quot;,
																											            &quot;price_male3&quot;:&quot;79.00&quot;,
																											            &quot;price_female&quot;:&quot;89.00&quot;,
																											            &quot;price_female2&quot;:&quot;79.00&quot;,
																											            &quot;price_female3&quot;:&quot;69.00&quot;,
																											            &quot;price_male_try&quot;:&quot;100.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;95.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;90.00&quot;,
																											            &quot;price_female_try&quot;:&quot;80.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;60.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;40.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;119.00&quot;,
																											            &quot;male2_price&quot;:&quot;99.00&quot;,
																											            &quot;male3_price&quot;:&quot;79.00&quot;,
																											            &quot;female_price&quot;:&quot;89.00&quot;,
																											            &quot;female2_price&quot;:&quot;79.00&quot;,
																											            &quot;female3_price&quot;:&quot;69.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:48,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Bauchlinie&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;25.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;20.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:49,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Achseln&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;79.00&quot;,
																											            &quot;price_male2&quot;:&quot;69.00&quot;,
																											            &quot;price_male3&quot;:&quot;59.00&quot;,
																											            &quot;price_female&quot;:&quot;59.00&quot;,
																											            &quot;price_female2&quot;:&quot;49.00&quot;,
																											            &quot;price_female3&quot;:&quot;39.00&quot;,
																											            &quot;price_male_try&quot;:&quot;90.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;70.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female_try&quot;:&quot;80.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;70.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;60.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;79.00&quot;,
																											            &quot;male2_price&quot;:&quot;69.00&quot;,
																											            &quot;male3_price&quot;:&quot;59.00&quot;,
																											            &quot;female_price&quot;:&quot;59.00&quot;,
																											            &quot;female2_price&quot;:&quot;49.00&quot;,
																											            &quot;female3_price&quot;:&quot;39.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:69,
																											            &quot;category_id&quot;:2,
																											            &quot;name&quot;:&quot;Brustwarzen&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;29.00&quot;,
																											            &quot;price_male2&quot;:&quot;24.00&quot;,
																											            &quot;price_male3&quot;:&quot;19.00&quot;,
																											            &quot;price_female&quot;:&quot;29.00&quot;,
																											            &quot;price_female2&quot;:&quot;24.00&quot;,
																											            &quot;price_female3&quot;:&quot;19.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;25.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;20.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;29.00&quot;,
																											            &quot;male2_price&quot;:&quot;24.00&quot;,
																											            &quot;male3_price&quot;:&quot;19.00&quot;,
																											            &quot;female_price&quot;:&quot;29.00&quot;,
																											            &quot;female2_price&quot;:&quot;24.00&quot;,
																											            &quot;female3_price&quot;:&quot;19.00&quot;
																											         }
																											      ]
																											   },
																											   {
																											      &quot;id&quot;:3,
																											      &quot;sort&quot;:3,
																											      &quot;name&quot;:&quot;Unterk\u00f6rper&quot;,
																											      &quot;created_at&quot;:&quot;-000001-11-29T23:06:32.000000Z&quot;,
																											      &quot;updated_at&quot;:&quot;2018-11-20T10:04:41.000000Z&quot;,
																											      &quot;services&quot;:[
																											         {
																											            &quot;id&quot;:50,
																											            &quot;category_id&quot;:3,
																											            &quot;name&quot;:&quot;Oberschenkel&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;159.00&quot;,
																											            &quot;price_male2&quot;:&quot;139.00&quot;,
																											            &quot;price_male3&quot;:&quot;109.00&quot;,
																											            &quot;price_female&quot;:&quot;139.00&quot;,
																											            &quot;price_female2&quot;:&quot;119.00&quot;,
																											            &quot;price_female3&quot;:&quot;105.00&quot;,
																											            &quot;price_male_try&quot;:&quot;180.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;170.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;160.00&quot;,
																											            &quot;price_female_try&quot;:&quot;160.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;150.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;140.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;159.00&quot;,
																											            &quot;male2_price&quot;:&quot;139.00&quot;,
																											            &quot;male3_price&quot;:&quot;109.00&quot;,
																											            &quot;female_price&quot;:&quot;139.00&quot;,
																											            &quot;female2_price&quot;:&quot;119.00&quot;,
																											            &quot;female3_price&quot;:&quot;105.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:51,
																											            &quot;category_id&quot;:3,
																											            &quot;name&quot;:&quot;Unterschenkel&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;139.00&quot;,
																											            &quot;price_male2&quot;:&quot;119.00&quot;,
																											            &quot;price_male3&quot;:&quot;99.00&quot;,
																											            &quot;price_female&quot;:&quot;119.00&quot;,
																											            &quot;price_female2&quot;:&quot;109.00&quot;,
																											            &quot;price_female3&quot;:&quot;95.00&quot;,
																											            &quot;price_male_try&quot;:&quot;150.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;140.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;130.00&quot;,
																											            &quot;price_female_try&quot;:&quot;130.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;120.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;110.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;139.00&quot;,
																											            &quot;male2_price&quot;:&quot;119.00&quot;,
																											            &quot;male3_price&quot;:&quot;99.00&quot;,
																											            &quot;female_price&quot;:&quot;119.00&quot;,
																											            &quot;female2_price&quot;:&quot;109.00&quot;,
																											            &quot;female3_price&quot;:&quot;95.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:52,
																											            &quot;category_id&quot;:3,
																											            &quot;name&quot;:&quot;F\u00fc\u00dfe&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;29.00&quot;,
																											            &quot;price_male2&quot;:&quot;19.00&quot;,
																											            &quot;price_male3&quot;:&quot;15.00&quot;,
																											            &quot;price_female&quot;:&quot;29.00&quot;,
																											            &quot;price_female2&quot;:&quot;19.00&quot;,
																											            &quot;price_female3&quot;:&quot;15.00&quot;,
																											            &quot;price_male_try&quot;:&quot;50.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female_try&quot;:&quot;50.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;30.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;29.00&quot;,
																											            &quot;male2_price&quot;:&quot;19.00&quot;,
																											            &quot;male3_price&quot;:&quot;15.00&quot;,
																											            &quot;female_price&quot;:&quot;29.00&quot;,
																											            &quot;female2_price&quot;:&quot;19.00&quot;,
																											            &quot;female3_price&quot;:&quot;15.00&quot;
																											         }
																											      ]
																											   },
																											   {
																											      &quot;id&quot;:4,
																											      &quot;sort&quot;:4,
																											      &quot;name&quot;:&quot;Intimzone&quot;,
																											      &quot;created_at&quot;:&quot;-000001-11-29T23:06:32.000000Z&quot;,
																											      &quot;updated_at&quot;:&quot;2018-11-20T10:04:41.000000Z&quot;,
																											      &quot;services&quot;:[
																											         {
																											            &quot;id&quot;:66,
																											            &quot;category_id&quot;:4,
																											            &quot;name&quot;:&quot;Bikini Zone&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;0.00&quot;,
																											            &quot;price_male2&quot;:&quot;0.00&quot;,
																											            &quot;price_male3&quot;:&quot;0.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;70.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;60.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;50.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;0.00&quot;,
																											            &quot;male2_price&quot;:&quot;0.00&quot;,
																											            &quot;male3_price&quot;:&quot;0.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:54,
																											            &quot;category_id&quot;:4,
																											            &quot;name&quot;:&quot;Ges\u00e4\u00df Komplett&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;139.00&quot;,
																											            &quot;price_male2&quot;:&quot;119.00&quot;,
																											            &quot;price_male3&quot;:&quot;99.00&quot;,
																											            &quot;price_female&quot;:&quot;99.00&quot;,
																											            &quot;price_female2&quot;:&quot;79.00&quot;,
																											            &quot;price_female3&quot;:&quot;69.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;120.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;100.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;80.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;139.00&quot;,
																											            &quot;male2_price&quot;:&quot;119.00&quot;,
																											            &quot;male3_price&quot;:&quot;99.00&quot;,
																											            &quot;female_price&quot;:&quot;99.00&quot;,
																											            &quot;female2_price&quot;:&quot;79.00&quot;,
																											            &quot;female3_price&quot;:&quot;69.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:67,
																											            &quot;category_id&quot;:4,
																											            &quot;name&quot;:&quot;Intimbereich&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;129.00&quot;,
																											            &quot;price_male2&quot;:&quot;119.00&quot;,
																											            &quot;price_male3&quot;:&quot;89.00&quot;,
																											            &quot;price_female&quot;:&quot;119.00&quot;,
																											            &quot;price_female2&quot;:&quot;109.00&quot;,
																											            &quot;price_female3&quot;:&quot;99.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;0.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;129.00&quot;,
																											            &quot;male2_price&quot;:&quot;119.00&quot;,
																											            &quot;male3_price&quot;:&quot;89.00&quot;,
																											            &quot;female_price&quot;:&quot;119.00&quot;,
																											            &quot;female2_price&quot;:&quot;109.00&quot;,
																											            &quot;female3_price&quot;:&quot;99.00&quot;
																											         },
																											         {
																											            &quot;id&quot;:68,
																											            &quot;category_id&quot;:4,
																											            &quot;name&quot;:&quot;Pofalte&quot;,
																											            &quot;description&quot;:&quot;&quot;,
																											            &quot;price_male&quot;:&quot;49.00&quot;,
																											            &quot;price_male2&quot;:&quot;39.00&quot;,
																											            &quot;price_male3&quot;:&quot;29.00&quot;,
																											            &quot;price_female&quot;:&quot;49.00&quot;,
																											            &quot;price_female2&quot;:&quot;39.00&quot;,
																											            &quot;price_female3&quot;:&quot;29.00&quot;,
																											            &quot;price_male_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male2_try&quot;:&quot;0.00&quot;,
																											            &quot;price_male3_try&quot;:&quot;0.00&quot;,
																											            &quot;price_female_try&quot;:&quot;40.00&quot;,
																											            &quot;price_female2_try&quot;:&quot;30.00&quot;,
																											            &quot;price_female3_try&quot;:&quot;25.00&quot;,
																											            &quot;treatments&quot;:8,
																											            &quot;male_price&quot;:&quot;49.00&quot;,
																											            &quot;male2_price&quot;:&quot;39.00&quot;,
																											            &quot;male3_price&quot;:&quot;29.00&quot;,
																											            &quot;female_price&quot;:&quot;49.00&quot;,
																											            &quot;female2_price&quot;:&quot;39.00&quot;,
																											            &quot;female3_price&quot;:&quot;29.00&quot;
																											         }
																											      ]
																											   },
																											   {
																											      &quot;id&quot;:5,
																											      &quot;sort&quot;:5,
																											      &quot;name&quot;:&quot;Sonstige&quot;,
																											      &quot;created_at&quot;:&quot;-000001-11-29T23:06:32.000000Z&quot;,
																											      &quot;updated_at&quot;:&quot;2018-11-20T10:04:42.000000Z&quot;,
																											      &quot;services&quot;:[
																											         
																											      ]
																											   }
																											]"
                                                currency="€"
                                                :category="5"
                                                :services="{}"
                                                :client="{&quot;id&quot;:<?=$customer_info->id?>,&quot;account_id&quot;:6,&quot;user_id&quot;:null,&quot;type&quot;:1,&quot;group&quot;:&quot;B&quot;,&quot;salutation&quot;:<?=$customer_info->salutation?>,&quot;first_name&quot;:&quot;<?=$customer_info->firstname?>&quot;,&quot;last_name&quot;:&quot;<?=$customer_info->lastname?>&quot;,&quot;street&quot;:&quot;<?=$customer_info->address1?>&quot;,&quot;zipcode&quot;:&quot;<?=$customer_info->zipcode?>&quot;,&quot;city&quot;:&quot;<?=$customer_info->city?>&quot;,&quot;country&quot;:&quot;<?=$customer_info->country == null ? 'DE' : $customer_info->country?>&quot;,&quot;phone_1&quot;:&quot;<?=$customer_info->phone?>&quot;,&quot;phone_2&quot;:&quot;&quot;,&quot;fax&quot;:&quot;&quot;,&quot;mobile&quot;:&quot;<?=$customer_info->mobile?>&quot;,&quot;email&quot;:&quot;<?=$customer_info->email?>&quot;,&quot;web&quot;:&quot;&quot;,&quot;birthday&quot;:&quot;<?=$customer_info->birthdate?>&quot;,&quot;nationality&quot;:&quot;0&quot;,&quot;job&quot;:null,&quot;kab_attentive&quot;:null,&quot;kab_treatments&quot;:null,&quot;kab_disturbing_areas&quot;:null,&quot;kab_disturbing_skin_problems&quot;:null,&quot;kab_skin_care_products&quot;:null,&quot;note&quot;:&quot;<?phpurlencode($customer_info->notes);?>&quot;,&quot;sms_treatment_reminder&quot;:0,&quot;sms_last_treatment_reminder_date&quot;:null,&quot;newsletter&quot;:<?=$customer_info->newsletter?>,&quot;lock&quot;:<?=$customer_info->locked?>,&quot;client_id_old&quot;:0,&quot;created_at&quot;:&quot;<?=$customer_info->createdt?>&quot;,&quot;updated_at&quot;:&quot;<?=$customer_info->lastvisit?>&quot;,&quot;deleted_at&quot;:null,&quot;full_name&quot;:&quot;<?=$customer_fullname?>&quot;}"
                                                :show-img="true"
                                                :payment="{&quot;RATES&quot;:&quot;Finanzierung&quot;,&quot;LUMP&quot;:&quot;Pauschal&quot;,&quot;DEBIT&quot;:&quot;Lastschrift&quot;,&quot;BAR&quot;:&quot;Bar \/ EC&quot;,&quot;PARTIAL&quot;:&quot;Teilzahlung&quot;}"
                                                :single-price="false"
                                                :num-treatments="8"
                                                :treatment-column="true"
                                                :old="[]">
                                            </services>
                                            <hr>
                                            <p>
                                                Das Behandlungsinstitut weist den Kunden darauf hin, dass die auf den
                                                folgenden Seiten
                                                dieses Behandlungsvertrages aufgedruckten Allgemeinen
                                                Geschäftsbedingungen
                                                des
                                                Behandlungsinstituts fester Bestandteil und Grundlage des
                                                Behandlungssvertrages sind. </p>
                                        </div>

                                        <div class="container">
                                            <hr>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="city">Ort</label>
                                                        <input class="form-control" v-model="city" ref="city"
                                                               name="city"
                                                               type="text"
                                                               value="Hamm" id="city">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="date">Datum</label>
                                                        <input class="form-control flatpickr" v-model="date" ref="date"
                                                               autocomplete="off"
                                                               name="date" type="text" value="<?=date("d.m.Y")?>"
                                                               id="date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="signature" data-by="client"></div>
                                                    <input class="signature" data-by="client"
                                                           name="signature[client][base30]"
                                                           type="hidden">
                                                    <input class="signature_svg" data-by="client"
                                                           name="signature[client][svg]"
                                                           type="hidden">

                                                    <div class="float-right">
                                                        <a class="clearSignature btn btn-default" data-by="client"><i
                                                                class="fa fa-times"></i></a>
                                                    </div>
                                                    <h5>Unterschrift Kunde/Erziehungsberechtigter</h5>
                                                </div>
                                                <div class="col-6">
                                                    <div class="signature" data-by="worker"></div>
                                                    <input class="signature" data-by="worker"
                                                           name="signature[worker][base30]"
                                                           type="hidden">
                                                    <input class="signature_svg" data-by="worker"
                                                           name="signature[worker][svg]"
                                                           type="hidden">

                                                    <div class="float-right">
                                                        <a class="clearSignature btn btn-default" data-by="worker"><i
                                                                class="fa fa-times"></i></a>
                                                    </div>
                                                    <h5>Unterschrift Therapeut</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bar">
                                            <div class="container text-uppercase text-center">
                                                Allgemeine Behandlungsvereinbarung
                                            </div>
                                        </div>

                                        <div class="container">
                                            <div class="small">

																							<h5>Allgemeine Geschäftsbedingungen</h5>
																							<h5>§ 1 Geltungsbereich</h5>
																							<p>Die vorliegenden Allgemeinen Geschäftsbedingungen (AGB) gelten zwischen der 
																							Medical Hairless & Esthetic Praxis in Hamm und unseren Kunden. Es gelten 
																							ausschließliche diese AGB. Abweichende Regelungen bedürfen der schriftlichen 
																							Zustimmung.</p>
																							<h5>§ 2 Vertragsinhalt</h5>
																							<p>Der Vertragsinhalt ergibt sich aus der zwischen uns, der Medical Hairless & Esthetic 
																							Praxis in Hamm, und unseren Kunden geschlossenen Behandlungsvereinbarung. 
																							<h5>§ 3 Leistungserfolg</h5>
																							<p>(1) Bei der zwischen Ihnen und uns geschlossenen Behandlungsvereinbarung handelt 
																							es sich um einen Dienstvertrag. Ein Erfolg ist von uns nicht geschuldet.</p>
																							<p>(2) Wir übernehmen auch keine Garantie für einen bestimmten Leistungserfolg. </p>
																							<h5>§ 4 Pflichten unserer Kunden</h5>
																							<p>(1) Durch den Abschluss der Behandlungsvereinbarung verpflichtet sich der Kunde, 
																							den bzw. die mit uns vereinbarten Behandlungstermine, falls Folgetermine vereinbart 
																							wurden, einzuhalten.</p>
																							<p>(2) Zur Sicherung der mit uns vereinbarten Termine ist eine Vorauszahlung in Höhe 
																							von 50,00 € zu leisten. Die Vorauszahlung ist jeweils zum Zeitpunkt der jeweiligen 
																							Vereinbarung eines Behandlungstermins zahlbar und fällig. Vor der Leistung der 
																							Vorauszahlung wird kein neuer Behandlungstermin vereinbart. </p>
																							<p>(3) Bei vertragsgemäßer Wahrnehmung der vereinbarten Termine werden die 
																							geleisteten Vorauszahlungen auf Wunsch des Kunden entweder ausgezahlt oder auf 
																							die Gesamtvergütung angerechnet.</p>
																							<p>(4) Termine, die aus vom Kunden zu vertretenen Gründen nicht eingehalten werden 
																							können, sind spätestens 48 Stunden vor dem vereinbarten Termin abzusagen.</p>
																							<p>(5) Unterbleibt die rechtzeitige Absage oder erscheint der Kunde zu einem 
																							vereinbarten Termin nicht, so sind wir berechtigt, die Vorauszahlung in Höhe von 50,00 
																							€ als Ausfallpauschale einzubehalten. Eine Verrechnung auf Folgetermine sowie eine 
																							Auszahlung entfallen in diesem Fall.</p>
																							<p>(6) Der Kunde ist, um einen ordnungsgemäßen Behandlungsverlauf zu sichern, 
																							verpflichtet, die auf dem Formular „Anamnese“ gestellten Fragen vollständig und 
																							wahrheitsgemäß zu beantworten. Können Sie bestimmte Fragen nicht beantworten, 
																							sind Sie verpflichtet, uns darauf hinzuweisen. Die zur Behandlung erforderlichen 
																							Informationen sind bei Nichtvorliegen nachzureichen. Wir behalten uns ausdrücklich 
																							vor, den Behandlungsbeginn von der Beantwortung der für die Behandlung 
																							erforderlichen Fragen abhängig zu machen. </p>
																							<p>(7) Änderungen des gesundheitlichen Zustandes sind uns unverzüglich und vor 
																							Behandlungsbeginn mitzuteilen.</p>
																							<p>(8) Unseren Anweisungen, Vorgaben und Empfehlungen vor, während sowie nach der 
																							Behandlung ist Folge zu leisten. </p>
																							<p>(9) Falls dies im Rahmen der Behandlung erforderlich ist und Sie von uns darauf 
																							hingewiesen werden, sind die zu behandelnden Körperareale einen Tag vor der 
																							Behandlung gemäß unserer Anweisungen von Ihnen vorzubereiten.</p>
																							<h5>§ 5 Zahlungsmodalitäten</h5>
																							<p>(1) Die Zahlungsmodalitäten ergeben sich aus dem zwischen Ihnen uns 
																							geschlossenen Behandlungsvertrag. Wenn nichts Gegenteiliges vereinbart wurde, 
																							zahlen Sie die jeweils anfallenden Kosten für den jeweiligen Behandlungstermin.</p>
																							<p>(2) Die vereinbarte Vergütung für die Gesamtbehandlung ist bei Abschluss des 
																							Behandlungsvertrages insgesamt zur Zahlung fällig, wenn dies ausdrücklich vereinbart 
																							wurde. </p>
																							<h5>§ 6 Unser Kündigungsrecht und Schadenersatz</h5>
																							<p>(1) Wird die Vereinbarung und Durchführung von Behandlungsterminen von Ihnen 
																							verweigert und/oder werden Termine durch Sie in mehr als drei Fällen erst innerhalb 
																							eines Zeitraums von weniger als 48 Stunden abgesagt, sind wir berechtigt, den 
																							Behandlungsvertrag mit Ihnen zu kündigen. </p>
																							<p>(2) Im Falle einer Kündigung nach § 6 Abs.1 dieser Bedingungen sind Sie verpflichtet, 
																							uns einen pauschalisierte Schadenersatzleistung in Höhe von 40 % des 
																							verbleibenden, durch die bisherigen Behandlungen nicht in Anspruch genommenen 
																							Behandlungspreises zu zahlen. Ihnen ist der Nachweis gestattet, dass uns kein 
																							Schaden oder ein wesentlich niedrigerer Schaden entstanden ist. Uns wird der 
																							Nachweis eines höheren Schadens vorbehalten.</p>
																							<p>(3) Der Schadenersatz nach § 6 Abs. 2 dieser Bedingungen sowie ein etwaiger, offener 
																							Restbetrag für in Anspruch genommene Behandlungen sind mit der Erklärung des 
																							Rücktritts durch uns sofort zahlbar und fällig. Wir behalten uns vor, unsere Ansprüche 
																							mit Ihren Rückzahlungsansprüchen zu verrechnen.</p>
																							<h5>§ 7 Haftung</h5>
																							<p>(1) Unsere Haftung sowie die unserer Mitarbeiter und Erfüllungsgehilfen für 
																							vertragliche Pflichtverletzungen sowie aus Delikt ist auf Vorsatz und grobe 
																							Fahrlässigkeit beschränkt. </p>
																							<p>(2) Dies gilt nicht bei Verletzung einer wesentlichen Vertragspflicht, d.h. einer Pflicht 
																							auf deren Einhaltung Sie als Kunde vertrauen und vertrauen dürfen. Bei leichter 
																							Fahrlässigkeit ist die Haftung jedoch auf den Ersatz des vorhersehbaren, 
																							typischerweise eintretenden Schaden begrenzt.</p>
																							<p>(3) Die Höhe eines etwaigen Schadenersatzanspruchs kann durch ein Mitverschulden 
																							Ihrerseits vermindert oder vollständig auf Null reduziert werden. Ein Mitverschulden 
																							kann insbesondere vorliegen,
																							<ul>- wenn Sie gegen Anweisungen unserer Mitarbeiter verstoßen;</ul>
																							<ul>- wenn Sie zum Zeitpunkt des Abschlusses der Behandlungsvereinbarung wissentlich 
																							falsche Angaben gemacht haben;</ul>
																							<ul>- wenn Sie die Anweisungen hinsichtlich der Vorbereitung zur jeweiligen Behandlung 
																							nicht beachtet haben;</ul>
																							<ul>- wenn Sie die Anweisungen zur Vor- und Nachbehandlung nicht beachtet haben.</ul>
																							<h5>§ 8 Sonstiges</h5>
																							<p>Dieser Vertrag unterliegt dem Recht der Bundesrepublik Deutschland.
																							</p>

                                            </div>
                                        </div>
                                        <div class="bar">
                                            <div class="container text-uppercase text-center">
                                                Datenschutzerklärungen
                                            </div>
                                        </div>

                                        <div class="container">
                                            <ul>
                                                <li>Ich erteile meine Einwilligung, dass das Behandlungsinstitut und die
                                                    Medical Hairless & Esthetic meine personenbezogenen Daten, die im Zusammenhang mit
                                                    dem
                                                    Behandlungsvertrag erhoben werden, erfassen, speichern, verarbeiten
                                                    und
                                                    für die Zwecke
                                                    des Behandlungsvertrages, zur Kundebetreuung und für statistische
                                                    Auswertungen nutzen
                                                    dürfen. Zu diesen Zwecken ist eine Weiterleitung der Daten auch an
                                                    den IT-Dienstleister von Medical Hairless & Esthetic estattet.
                                                </li>
                                                <li>Ich erteile meine Einwilligung, dass meine personenbezogenen Daten
                                                    sowie
                                                    Bildaufnahmen
                                                    meines Gesichtes zum Zweck der Hautanalyse an Medical Hairless & Esthetic
                                                    weitergeleitet und
                                                    von diesem erfasst, gespeichert, verarbeitet und zu diesem Zweck
                                                    verwendet werden.
                                                </li>
                                                <li>Ich willige ein, dass mich die Medical Hairless & Esthetic an meine
                                                    bevorstehenden
                                                    Behandlungstermine erinnert und mich per SMS über zukünftige
                                                    Angebote
                                                    und Aktionen von
                                                    Medical Hairless & Esthetic informiert.
                                                </li>
                                            </ul>

                                            <p>
                                                Ich weiß, dass ich zur Erteilung der Einwilligungen nicht verpflichtet
                                                bin
                                                und dass mir aus
                                                einer Verweigerung der Einwilligungen keine rechtlichen Nachteile
                                                entstehen
                                                dürfen. Die
                                                Einwilligungen sind jederzeit gegenüber dem Behandlungsinstitut unter
                                                den
                                                dort genannten
                                                Kontaktadressen widerrufbar.
                                            </p>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="city">Ort</label>
                                                        <input class="form-control" v-model="city" name="city"
                                                               type="text"
                                                               id="city">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="date">Datum</label>
                                                        <input class="form-control flatpickr" v-model="date" name="date"
                                                               type="text"
                                                               value="<?=date("d.m.Y")?>" id="date">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="signature" data-by="consent_statement"></div>
                                                    <input class="signature" data-by="consent_statement"
                                                           name="signature[consent_statement][base30]" type="hidden">
                                                    <input class="signature_svg" data-by="consent_statement"
                                                           name="signature[consent_statement][svg]" type="hidden">

                                                    <div class="float-right">
                                                        <a class="clearSignature btn btn-default"
                                                           data-by="consent_statement"><i
                                                                class="fa fa-times"></i></a>
                                                    </div>
                                                    <h5>Unterschrift Kunde/Erziehungsberechtigter</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bar">
                                            <div class="container text-uppercase text-center">
                                                Behandlungsinformationen
                                            </div>
                                        </div>
                                        <div class="container">


                                            <h4>Was ist vor der Behandlung zu beachten?</h4>

                                            <p>
                                                Die zu behandelnden Stellen sollten mindestens 4 Wochen vor und nach der
                                                Behandlung keiner
                                                direkten Sonnen-Bestrahlung ausgesetzt sein und dürfen weder mit
                                                UV-Bestrahlung im Solarium
                                                noch mit Selbstbräuner behandelt werden. Die Stellen sollten möglichst
                                                ungebräunt sein.
                                                Durch die Bräune können Hautunverträglichkeiten entstehen, für die wir
                                                keine
                                                Haftung
                                                übernehmen.
                                            </p>

                                            <ul>
                                                <li>Kosmetika (z.B. Cremes, Make-Up, Deodorant, etc.) müssen vor der
                                                    Behandlung sorgfältig
                                                    entfernt werden.
                                                </li>
                                                <li>Sollte im Behandlungsareal schon einmal Herpes aufgetreten sein,
                                                    informieren Sie Ihren
                                                    Therapeuten bitte unbedingt vor der Behandlung darüber.
                                                </li>
                                                <li>Falls Sie folgende Medikamente in den letzten Wochen eingenommen
                                                    haben,
                                                    ist der
                                                    Therapeut unbedingt zu informieren: Antibiotikum, Kortison,
                                                    Johanniskraut.
                                                </li>
                                            </ul>

                                            <h4>Was ist nach der Behandlung zu beachten?</h4>

                                            <p>Unmittelbar nach der Behandlung ist die Haut je nach Stärke der
                                                Bestrahlung
                                                einige Stunden
                                                lang gerötet und manchmal leicht geschwollen.</p>

                                            <ul>
                                                <li>Sollten Krusten entstehen, dürfen diese nicht durch Kratzen oder
                                                    Reiben
                                                    entfernt
                                                    werden.
                                                </li>
                                                <li>Waschen Sie sich in den ersten zwei bis drei Tagen nach der
                                                    Behandlung
                                                    nur mit kaltem
                                                    oder lauwarmem Wasser.
                                                </li>
                                                <li>Tupfen Sie die Stellen vorsichtig trocken, ohne zu rubbeln.</li>
                                                <li>Saunabesuche sind erst eine Woche nach der Behandlung wieder
                                                    möglich.
                                                </li>
                                            </ul>

                                            <h4>Was ist während der Behandlung zu beachten?</h4>

                                            <p>Sollten sich Veränderungen Ihres Gesundheitszustandes oder Ihrer
                                                Medikamenteneinnahme während
                                                der Behandlung einstellen, müssen Sie uns darüber umgehend
                                                informieren.</p>

                                            <h4>Welche Nebenwirkungen und Komplikationen können auftreten?</h4>

                                            <p>In der Regel verläuft die Laserbehandlung ohne wesentliche
                                                Komplikationen.
                                                Trotz größter
                                                Sorgfalt kann es jedoch während und nach dem Eingriff zu folgenden
                                                Nebenwirkungen
                                                kommen.</p>

                                            <ul>
                                                <li>Krustenbildung</li>
                                                <li>Oberflächliche Infektionen des behandelten Hautareals /
                                                    Wundheilstörungen
                                                </li>
                                                <li>Provokation eines Herpes (Fieberbläschen) oder (extrem selten) einer
                                                    Gürtelrose in dem
                                                    behandelten Areal
                                                </li>
                                                <li>Allergie</li>
                                                <li>Bleibende Farbveränderungen der Haut (Hypo- bzw. Hyperpigmentierung)
                                                </li>
                                                <li>Narbenbildung, narbige Hauteinziehungen und Narbenwucherungen
                                                    (anlagenbedingte wulstige
                                                    Narben)
                                                </li>
                                                <li>Verbrennung der Haut</li>
                                                <li>Synchronisation des Haarwachstums kann vorübergehend zu einer
                                                    stärkeren
                                                    Behaarung
                                                    führen
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bar">
                                            <div class="container text-uppercase text-center">
                                                Anamnese
                                            </div>
                                        </div>

                                        <div id="app">
                                            <div class="container">

                                                <h4>Haben Sie sich zuvor einer Laser-, Nadel-, oder IPL- Behandlung
                                                    unterzogen?</h4>
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <input v-model="laser" name="options[laser]" type="radio"
                                                               value="0">
                                                        Nein </label>
                                                    <label class="radio-inline">
                                                        <input v-model="laser" name="options[laser]" type="radio"
                                                               value="1">
                                                        Ja </label>
                                                </div>
                                                <div class="form-group" v-if="laser == 1">
                                                    <input class="form-control"
                                                           placeholder="Welche Behandlung und wann?"
                                                           name="options[laser_yes]" type="text">
                                                </div>

                                                <h4>Nehmen Sie derzeit Medikamente ein?</h4>
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <input v-model="medic" name="options[medic]" type="radio"
                                                               value="0">
                                                        Nein </label>
                                                    <label class="radio-inline">
                                                        <input v-model="medic" name="options[medic]" type="radio"
                                                               value="1">
                                                        Ja </label>
                                                </div>
                                                <div class="form-group" v-if="medic == 1">
                                                    <input class="form-control"
                                                           placeholder="Welche Medikamente nehmen Sie?"
                                                           name="options[medics]" type="text">
                                                </div>

                                                <h4>Besteht zur Zeit eine Schwangerschaft?</h4>
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <input name="options[pregnant]" type="radio" value="0"> Nein
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input name="options[pregnant]" type="radio" value="1"> Ja
                                                    </label>
                                                </div>

                                                <h4>Waren Sie in den letzten 4 Wochen einer intensiven Sonnenstrahlung
                                                    ausgesetzt?</h4>
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <input name="options[intensivesun]" type="radio" value="0"> Nein
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input name="options[intensivesun]" type="radio" value="1"> Ja
                                                    </label>
                                                </div>

                                                <h4>Leiden Sie unter einer der folgenden Krankheiten oder waren Sie in
                                                    der
                                                    Vergangenheit
                                                    davon betroffen?</h4>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Aids]"
                                                                           type="checkbox"
                                                                           value="1"> Aids
                                                                    (HIV) </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Hepatitis]"
                                                                           type="checkbox" value="1">
                                                                    Hepatitis </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Epilepsie]"
                                                                           type="checkbox" value="1">
                                                                    Epilepsie </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Hautkrebs]"
                                                                           type="checkbox" value="1">
                                                                    Hautkrebs </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Diabetes]"
                                                                           type="checkbox" value="1">
                                                                    Diabetes </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Herzschrittmacher]"
                                                                           type="checkbox"
                                                                           value="1"> Herzschrittmacher </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Hauterkrankungen]"
                                                                           type="checkbox"
                                                                           value="1"> Hauterkrankungen </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Herpes]"
                                                                           type="checkbox"
                                                                           value="1">
                                                                    Herpes </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Narbenbildung]"
                                                                           type="checkbox"
                                                                           value="1"> Narbenbildung </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="options[diseases][Keine Krankheit]"
                                                                           type="checkbox"
                                                                           value="1"> nein, keine der Krankheiten
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h4>Besteht eine Allergie?</h4>
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <input v-model="allergic" name="options[allergic]" type="radio"
                                                               value="0"> Nein
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input v-model="allergic" name="options[allergic]" type="radio"
                                                               value="1"> Ja
                                                    </label>
                                                </div>
                                                <div class="form-group" v-if="allergic == 1">
                                                    <input class="form-control" placeholder="Wenn ja, welche?"
                                                           name="options[allergic_what]"
                                                           type="text">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="bar mb-0">
                                            <div class="container text-uppercase text-center">
                                                Einverständniserklärung
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">

                                            <p>Ich erkläre, dass ich</p>

                                            <ul>
                                                <li>den vorstehenden Anamnesebogen nach bestem Wissen und Gewissen
                                                    ausgefüllt habe;
                                                </li>
                                                <li>weiß, dass durch falsche Angaben das Risiko von unerwünschten
                                                    Nebenwirkungen und
                                                    Komplikationen stark erhöht wird;
                                                </li>
                                                <li>die vorstehenden Behandlungsinformationen erhalten, sorgfältig
                                                    gelesen
                                                    und verstanden
                                                    habe
                                                </li>
                                                <li>Kenntnis davon habe, dass die in den Behandlungsinformationen unter
                                                    „Nebenwirkungen und
                                                    Komplikationen“ genannten Folgen auftreten können;
                                                </li>
                                                <li>
                                                    von folgendem Mitarbeiter in einem Aufklärungsgespräch ausführlich
                                                    über
                                                    die geplante
                                                    Behandlung und die damit verbundenen Risiken informiert worden bin:

                                                </li>
                                            </ul>
                                            <div class="form-group">
                                                <select class="form-control" name="therapist">
                                                    <option disabled value="">Terapist Secin</option>
                                                    <?php foreach ($therapist_data as $therapist):?>
                                                    <option
                                                        <?=$therapist->id == "1" ? "selected" : null?> value="<?=$therapist->id?>"><?=$therapist->name?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>

                                            <p>Ich habe keine weiteren Fragen, fühle mich ausreichend aufgeklärt und
                                                willige
                                                hiermit in die
                                                geplante Behandlung ein.</p>

                                            <hr>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="city">Ort</label>
                                                        <input class="form-control" v-model="city" name="city"
                                                               type="text"
                                                               id="city">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="date">Datum</label>
                                                        <input class="form-control flatpickr" v-model="date" name="date"
                                                               type="text"
                                                               value="<?=date("d.m.Y")?>" id="date">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="signature" data-by="consent_form"></div>
                                                    <input class="signature" data-by="consent_form"
                                                           name="signature[consent_form][base30]"
                                                           type="hidden">
                                                    <input class="signature_svg" data-by="consent_form"
                                                           name="signature[consent_form][svg]"
                                                           type="hidden">

                                                    <div class="float-right">
                                                        <a class="clearSignature btn btn-default"
                                                           data-by="consent_form"><i
                                                                class="fa fa-times"></i></a>
                                                    </div>
                                                    <h5>Unterschrift Kunde/Erziehungsberechtigter</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container">
                                            {{--                                        <button type="button" id="saveContract" name="save" class="btn btn-primary">--}}
                                            {{--                                            Speichern--}}
                                            {{--                                        </button>--}}
                                            <input class="upload_contract" id="saveContract"
                                                   type='submit' name='submit'
                                                   value='Speichern'>
                                            <label id="showalert" for="submit" style="display: none"></label>
                                            <br><br><br><br><br>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div id="main_close_on_contract" class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="be-heading mb-10">
                        <div style="padding-left: 15px;" class="row mb-10">
                            <h4>Lutfen bir musteri secin: </h4><a
                                style="padding-left: 15px; padding-top: 2px; font-style: oblique"
                                href="{{route("backend.client")}}">Musteri Listesi..</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>

@endsection

@push('scripts')
    {{--        <script src="{{asset('public/assets/js/fontawesome.min.js')}}"></script>--}}
    {{--        <script src="{{asset('public/assets/js/fontawesome-all.min.js')}}"></script>--}}
    <script src="{{asset('public/assets/js/de.js')}}"></script>
    <script src="{{asset('public/assets/js/signaturepad.js')}}"></script>
    <script src="{{asset('public/assets/js/contract.js')}}"></script>
    <script type="text/javascript">
        $('#iban').on('keyup paste', function () {
            var iban = $(this).val();
            if (iban.length > 16) {
                $.get('https://openiban.com/validate/' + iban + '?getBIC=true&validateBankCode=true', function (data) {
                    if (data.valid) {
                        $('#bic').val(data.bankData.bic);
                    }
                });
            }
        });
        // $('#showalert').click(function (){
        //     if(){
        //         console.log("secilmis");
        //     }
        //     else{
        //         console.log("secilmemis");
        //     }
        // });
        function checkBeforeSubmit() {

            if(document.getElementsByClassName('table-warning').length == 0){

                var target = document.getElementById("showalert");
                target.innerHTML = "Behandlung erforderlich...";
                target.style.color = "#f11803";
                target.style.display = "block";

                setTimeout(function () {
                    $("#showalert").fadeOut("slow");
                }, 2000);

                return false;
            }

            if(!document.getElementsByName("signature[client][base30]")[0].value ||
                !document.getElementsByName("signature[worker][base30]")[0].value ||
                !document.getElementsByName("signature[consent_statement][base30]")[0].value ||
                !document.getElementsByName("signature[consent_form][base30]")[0].value){

                var target = document.getElementById("showalert");
                target.innerHTML = "Unterschrift erforderlich...";
                target.style.color = "#f11803";
                target.style.display = "block";

                setTimeout(function () {
                    $("#showalert").fadeOut("slow");
                }, 2000);

                return false;
            }
        }
    </script>
    <script !src="">
        $(document).ready(function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var id = url.searchParams.get("id");
            if (id !== null) {
                var element_side = document.getElementById("side_close_on_contract");
                var element_main = document.getElementById("main_close_on_contract");
                var element_header = document.getElementById("header_close_on_contract");
                element_side.classList.add("active");
                element_main.classList.add("active");
                element_header.classList.add("active");
            }
        });
    </script>
@endpush
