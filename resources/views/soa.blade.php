<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>SOA</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/plugin/bootstrap.min.css') }}">
    <style>
        @media print {
            .page-break {
                page-break-after: always
            }
        }

        @page {
            size: auto;
        }

        .col {
            font-family: "Arial Narrow", Arial, sans-serif;
            font-size: 15px;
            letter-spacing: 0.12em;
        }
    </style>
</head>

<body style="background: white">

    <div class="container-fluid">

        <div class="row">
            <div class="col text-center">
                <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" style="opacity: .8; width:100px;height:100px; position:absolute; left:120px; top:15px; z-index:1">
                <p> Republic of the Philippines <br> DEPARTMENT OF HEALTH </br> Western Visayas CHD </p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col text-center">
                <h3>STATEMENT OF ACCOUNT</h3>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col text-center">
                <p>To bill the amount of <br>
                <h4>(₱ {{ number_format($salary->soa, 2) }} ) </h4>
                </p>
                <h5 class="text-capitalize"> {{ $amount_to_words }} Pesos Only</h5>
                <p>as payment for services rendered covering the period of {{ $period }}. </p>
                <br>

                <h6>__________________________________________ <br>
                    {{ $emp->employee_name }} <br> {{ $emp->position }}
                </h6>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <hr>
    <br>
    <br>
    <div class="row">
        <div class="col text-center">
            <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" style="opacity: .8; width:100px;height:100px; position:absolute; left:120px; top:15px; z-index:1">
            <p> Republic of the Philippines <br> DEPARTMENT OF HEALTH </br> Western Visayas CHD </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col text-center">
            <h2>STATEMENT OF ACCOUNT</h2>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col text-center">
            <p>To bill the amount of <br>
            <h4>(₱ {{ number_format($salary->soa, 2) }} ) </h4>
            </p>
            <h5 class="text-uppercase"> {{ $amount_to_words }} PESOS ONLY</h5>
            <p>as payment for services rendered covering the period of {{ $period }}. </p>
            <br>

            <h6>__________________________________________ <br>
                {{ $emp->employee_name }} <br> {{ $emp->position }}
            </h6>
        </div>
    </div>
    </div>
</body>

</html>