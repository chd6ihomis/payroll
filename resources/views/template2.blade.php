<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>Payroll - Cashier Copy</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/plugin/bootstrap.min.css') }}">
    <style>
        @page {
            size: auto;
        }

        .page-break {
            page-break-inside: avoid;
            page-break-after: always;
        }

        table {
            font-family: "Arial Narrow", Arial, sans-serif;
            font-size: 18px;
            text-align: center;
            padding: 30px 30px;
            letter-spacing: 0.12em;
        }
		
		table, th, td {
		  border: 1px solid;
		  border-top: 1px solid;
		}
    </style>
</head>

<body style="background: white">

    <div class="container-fluid">

        <div class="row">
            <div class="col">
                <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" class="float-left" style="opacity: .8; width:110px;height:110px;">
                <p class="text-center">DEPARTMENT OF HEALTH </br> Western Visayas CHD </br> <em>PAYROLL CASHIER COPY</em> </p>
                <h4 class="text-center">{{ \DB::table('office')->where('id', auth()->user()->office)->first()->division }}</h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col text-left">
                <h5>To payment of services rendered as for the period {{ $period }} - {{ $desc }}</h5>
            </div>
        </div>

        @for($i = 1; $i <= $pages; $i++)
        <div class="row ">

            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">NO.</th>
                        <th rowspan="2">Employee Name</th>
                        <th rowspan="2">Position</th>
                        <th rowspan="2">Net Amt </br> Received</th>
                        <th rowspan="2">LBP-ATM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaries->slice(($i - 1) * $rows)->take(20) as $key=> $salary)

                    <tr>
                        <th scope="row"> {{ $key + 1 }}
                        </th>
                        <th scope="row" class="text-left">{{ $salary->employee_name }}</th>
                        <td scope="row">{{ $salary->position}}</td>
                        <th scope="row"> {{ number_format($salary->net_amt, 2) }} </th>
                        <th scope="row"> {{ $salary->lbp_num }} </th>
                    </tr>

                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="text-right grandtotal" colspan="2">SUB TOTAL</th>
                        <th class="grandtotal">{{ number_format($salaries->slice(($i - 1) * $rows)->take(20)->sum('net_amt'), 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
    </div>
        @if($i < $pages)
        <div class="page-break"></div>
        @endif
    @endfor





    <div class="row">
        <ul class="list-group">
            <li class="list-group-item">
                <h4>GRAND TOTAL</h4>
            </li>
            <li class="list-group-item">
                <h4>{{ number_format($salaries->sum('net_amt'), 2) }}</h4>
            </li>
        </ul>
    </div>


    </div>
</body>

</html>