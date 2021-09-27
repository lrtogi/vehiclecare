<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Print Kpi</title>
        <style>

            <!--@page { size: 21cm 32cm; margin: 0.5cm 1.4cm 0.5cm 0.1cm  }-->

            /*style="padding: 1px; border: none; font-family: arial,sans-serif;font-size: 8px;  font-weight: 500;"*/
            html {
                padding: 0 0 0 0;
                /*margin: 70px 0 0 0;*/
                margin: 0 0 0 0;
                font-size: 8px;
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

            .tableBorder {
                border-spacing: 0;
                border: 0px;
            }
            .tableBorder th{
                padding: 1px;
                border-spacing: 0;
                border: 1px solid black;
                border-left: 1px solid black;
                border-right: none;
                text-align: center;
            }

            .tableBorder td:nth-child(4),
            .tableBorder td:nth-child(5),
            .tableBorder td:nth-child(6),
            .tableBorder td:nth-child(7),
            .tableBorder td:nth-child(8) {
                text-align: right;
            }


            .tableBorder th:last-child{ 
                border-right: 1px solid black;
            }
            .tableBorder td{
                border-spacing: 0;
                border: none;
                border-left: none;
                border-right: none;
                border-top: none;
            }
            .tableBorder td:last-child{ 
                border-right: none;
            }
            .footer { 
                font-family: arial,sans-serif; 
                position: fixed; 
                bottom: 0; 
                right: 0;
            }
            .footer .page:after { 
                font-family: arial,sans-serif; 
                font-size: 8px; 
                content: "Page " counter(page);
            }

            .table {
                width: 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }
            .table > thead > tr > th,
            .table > tbody > tr > th,
            .table > tfoot > tr > th,
            .table > thead > tr > td,
            .table > tbody > tr > td,
            .table > tfoot > tr > td {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid black;
            }
            .table > thead > tr > th {
                vertical-align: bottom;
                border-bottom: 1px solid black;
            }
            .table > caption + thead > tr:first-child > th,
            .table > colgroup + thead > tr:first-child > th,
            .table > thead:first-child > tr:first-child > th,
            .table > caption + thead > tr:first-child > td,
            .table > colgroup + thead > tr:first-child > td,
            .table > thead:first-child > tr:first-child > td {
                border-top: 0;
            }
            .table > tbody + tbody {
                border-top: 2px solid black;
            }
            .table .table {
                background-color: #fff;
            }
            .table-condensed > thead > tr > th,
            .table-condensed > tbody > tr > th,
            .table-condensed > tfoot > tr > th,
            .table-condensed > thead > tr > td,
            .table-condensed > tbody > tr > td,
            .table-condensed > tfoot > tr > td {
                padding: 5px;
            }
            table.table-bordered {
                border-collapse: collapse;
                border: 1px solid black;
            }
            table.table-bordered td{
                border: 1px solid black;
                padding: 1px 2px;
            }
            table.table-bordered th{
                border: 1px solid black;
            }
            tr { page-break-inside: avoid }
            thead th { 
                height: 1cm;
                font-size: 12px;
                background-color: #cccccc;
            }
            tbody td {
                height: 1cm;
                font-size: 12px;
                text-align: center;
            }
            .colHead{
                font-size: 12px;
            }
            hr{
                border-collapse: collapse;
                border: 1px solid black;
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
            <table class="table-bordered fz15" cellspacing="0" cellpadding="0" width="100%" style="clear: both; margin-bottom: 1px;">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>EVENT</th>
                        <th>NIP</th>
                        <th>NAME</th>
                        <th>LOCATION</th>
                        <th>IMAGE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$transaction->order_date}}</td>
                        <td>{{$customerVehicle->customer_name}}</td>
                        <td><div id="picture">{{ QrCode::generate("$transaction->transaction_id") }}</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
