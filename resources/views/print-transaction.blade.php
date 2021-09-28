<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{ $header }}</title>
        <style>

            <!--@page { size: 21cm 32cm; margin: 0.5cm 1.4cm 0.5cm 0.1cm  }-->

            /*style="padding: 1px; border: none; font-family: arial,sans-serif;font-size: 8px;  font-weight: 500;"*/
            html {
                padding: 0 0 0 0;
                /*margin: 70px 0 0 0;*/
                margin: 0 0 0 0;
                font-size: 10px;
                position: relative;
            }

            body {
                margin: 0;
                font-family: arial, sans-serif;
                overflow:hidden;
            }

            .txt-top{
                vertical-align: text-top;
            }

            .fz14 {
                /*dulu fz 12*/
                font-size: 14px;
            }
            .fz15 {
                /*dulu fz 13*/
                font-size: 15px;
            }
            .fz12 {
                /*dulu fz 10*/
                font-size: 13px; 
            }
            .fz10 {
                /*dulu fz 10*/
                font-size: 10px; 
            }

            .header-print {
                clear: both;
                border-bottom: 1px solid black;
            }

            .header-print .left-header {
                display: inline-block;
                float:left;
                width: 55%;
            }

            .header-print .right-header {
                display: inline-block;
                width: 45%;
            }


            .main-wrapper .title-report {
                font-size: 16px;
                font-weight: 700;
                margin: 5px 0;
                text-align: center;
            }
            #picture{
                width: 150px;
                height: 200px;
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
                margin: auto;
            }
        </style>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-pager margbot20 none-usul">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="nomarg text-left word-straight">
                                            <label class="col-form-label">Order Date : <label class="col-form-label"><b>{{ date_format(date_create($model->order_date), "d-m-Y") }}</b></label></label>
                                        </p>
                                    </div>
                                </div>
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="nomarg text-left word-straight">
                                            <label class="col-form-label">Customer Name : <label class="col-form-label"><b>{{ $model->customer_name }}</b></label></label>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="nomarg text-left word-straight">
                                            
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="nomarg text-left word-straight">
                                            <label class="col-form-label">Police Number : <label class="col-form-label"><b>{{ $model->police_number }}</b></label></label>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="nomarg text-left word-straight">
                                            
                                        </p>
                                    </div>
                                </div>
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="nomarg text-left word-straight">
                                            <label class="col-form-label">Vehicle Name : <label class="col-form-label"><b>{{ $model->vehicle_name }}</b></label></label>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="nomarg text-left word-straight">
                                            
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margbot20">
                            <div class="col-md-7">
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="text-left">
                                            <label class="col-form-label">Queue No : <label class="col-form-label"><b>{{ $model->index }}</b></label> </label>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="nomarg text-left">
                                            
                                        </p>
                                    </div>
                                </div>
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="text-left">
                                            <label class="col-form-label">Total Price : <label class="col-form-label"><b>{{ $model->total_price }}</b></label></label>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="nomarg text-left">
                                            
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row input-wrapper margbot10">
                                    <div class="col-md-3">
                                        <p class="text-left">
                                            <label class="col-form-label">Status : <label class="col-form-label"><b>{{ $model->status }}</b></label></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div style="margin: 0 auto;">{{ QrCode::generate($model->transaction_id) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
