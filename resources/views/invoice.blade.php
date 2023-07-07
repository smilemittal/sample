<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} Invoice {{ $invoice->number }}</title>
    <style>
        html {
            font-family: sans-serif;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        html {
            font-size: 62.5%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333333;
            background-color: #ffffff;
        }

        p {
            margin: 0 0 10px;
        }

        clearfix:before,
        .container:before,
        .container:after,
        .row:before,
        .row:after {
            content: " ";
            display: table;
        }

        .clearfix:after,
        .container:after,
        .row:after {
            clear: both;
        }

        .container {
            margin-right: auto;
            margin-left: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        @media (min-width: 768px) {
            .container {
                width: 750px;
            }
        }

        @media (min-width: 992px) {
            .container {
                width: 970px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                width: 1170px;
            }
        }

        .row {
            margin-left: -15px;
            margin-right: -15px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
        }

        h1, h2, h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        h4, h5, h6 {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 36px;
        }

        h4 {
            font-size: 18px;
        }

        h5 {
            font-size: 14px;
        }

        h1 {
            margin: 0.67em 0;
        }

        h1 {
            display: inline-block;
        }

        .invoice-details {
            float: right;
        }

        .invoice-details span {
            font-weight: normal;
            font-size: .9em;
        }

        .total {
            float: right;
        }

        .total .amount {
            font-weight: normal;
        }

        table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
            border-top: 0;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        table {
            max-width: 100%;
            background-color: transparent;
        }

        table {
            margin-top: 50px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
        }

        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #dddddd;
        }

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #dddddd;
        }

        thead th {
            border: none !important;
        }

        th {
            text-align: left;
        }

        td, th {
            padding: 0;
        }

        thead {
            background: #005e54;
            color: white;
        }

        .table-striped > tbody > tr:nth-child(odd) > td, .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #f9f9f9;
        }

        .notice {
            background: #d7d7d7;
            padding: 2em;
            clear: both;
            margin-top: 6em;
        }

        .col {
            float: left;
            width: 300px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <img src="{{ asset('images/Logo.svg') }}" alt="{{ config('app.name') }}">
        <div class="invoice-details">
            <h2 contenteditable>Invoice Number: {{ $invoice->number }}</h2>
            <h4 contenteditable>Date of Invoice:&nbsp;
                <span id="date-of-invoice">
                    {{ $invoice->date()->toFormattedDateString() }}
                </span>
            </h4>
        </div>
    </div>
    <div class="row billing__references">
        <div class="col">
            <h4>Bill To:</h4>
            <h5>
                {{ $user->name }}
                <small>({{ $user->email }})</small>
            </h5>
            <p>
                @foreach(optional($user->billingAddress)->printable ?? [] as $address)
                    <span>{{ $address }}</span>
                    @if($address)
                        <br />
                    @endif
                @endforeach
            </p>
        </div>
        <div class="col">
            <h4>From:</h4>
            <h5>{{ config('app.name') }}</h5>
            <p>
                @foreach($fromAddress ?? [] as $address)
                    <span>{{ $address }}</span>
                    @if($address)
                        <br />
                    @endif
                @endforeach
            </p>
        </div>
    </div>
    <div class="row">
        <table class="lines table table-striped">
            <thead>
            <tr>
                <th>Qty</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            <tr contenteditable>
                <td>1</td>
                <td>{{ config('app.name') }} Subscription Dues</td>
                <td>{{ $invoice->total() }}</td>
            </tr>
            </tbody>
        </table>
        <div class="total">
            <h4>Total: <span class="amount">{{ $invoice->total() }} AUD</span></h4>
        </div>
        <div class="notes notice" contenteditable>
            <p>Thank You!</p>
        </div>
    </div>
</div>
</body>
</html>
