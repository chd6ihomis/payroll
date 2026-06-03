<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>General Payroll</title>

    <!-- Bootstrap core CSS -->
   
    <link rel="stylesheet" href="{{ asset('css/plugin/bootstrap.min.css') }}">
    <style>
        @page {
            size: auto;
            margin: 25mm 25mm 25mm 25mm;
        }

        .statements {
            font-size: 15px;
            font-style: italic;
        }

        .page-break {
            page-break-inside: avoid;
            page-break-after: always;
        }
		
		table, th, td {
		  border: 1px solid;
		  border-top: 1px solid;
		}
    </style>
    @if($style === 3)
    <style>
        table {
            font-family: "Arial Narrow", Arial, sans-serif;
            font-size: 11px;
            text-align: center;
            padding: 10px 10px;
        }
    </style>
    @elseif($style === 2)
    <style>
        table {
            font-family: "Arial Narrow", Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }
    </style>
    @else ($style === 1 || $style === 0)

    <style>
        table {
            font-family: "Arial Narrow", Arial, sans-serif;
            font-size: 15px;
            text-align: center;
            padding: 30px 30px;
        }
    </style>
    @endif
</head>

<body class="login-page" style="background: white">

    <div class="container-fluid">

        <div class="row">
            <div class="col">
                <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" class="float-left" style="opacity: .8; width:110px;height:110px;">
                <p class="text-center">DEPARTMENT OF HEALTH </br> Western Visayas CHD </br> GENERAL PAYROLL</p>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col text-left">
                <p>To payment of services rendered as for the period {{ $period }} of {{\DB::table('office')->where('id', auth()->user()->office)->first()->division}} Contract of Service - {{ $desc }}</p>
            </div>
        </div>

        @for($i = 1; $i <= $pages; $i++) <div class="row ">
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">NO.</th>
                        <th rowspan="2">TIN</th>
                        @if($pagibig)
                        <th rowspan="2"> Pag-Ibig <br> No.</th>
                        @endif

                        @if($sss)
                        <th rowspan="2"> SSS No.</th>
                        @endif

                        @if($philhealth)
                        <th rowspan="2"> PhilHealth <br> No.</th>
                        @endif

                        <th rowspan="2">Employee Name</th>
                        <th rowspan="2">Position</th>
                        <th rowspan="2">Monthly <br> Rate</th>
                        <th rowspan="2">{{ substr($period,0,3) }} <br> {{ substr($period, 4, 11)}}</th>
                        <th rowspan="1" colspan="3">Absences/Late/ </br> Undertime</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">SOA</th>
                        <th rowspan="2">Coop</th>
                        <th rowspan="2">Coop <br> Loan</th>
                        <th rowspan="2">TAX</th>

                        @if($pagibig)
                        <th rowspan="2">Pag-Ibig</th>
                        @endif

                        @if($sss)
                        <th rowspan="2">SSS</th>
                        @endif

                        @if($philhealth)
                        <th rowspan="2">PhilHealth</th>
                        @endif
						
                        @if($comm_allowance)
                        <th rowspan="2">Comm <br> Allowance</th>
                        @endif

                        <th rowspan="2">Net Amt </br> Received</th>
                        <th rowspan="2">LBP-ATM</th>
                        <th rowspan="2">Remarks</th>
                    </tr>
                    <tr>
                        <th scope="col">Day</th>
                        <th scope="col">Hrs</th>
                        <th scope="col">Mins</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries->slice(($i - 1) * $rows)->take(10) as $key => $salary)
                    <tr>
                        <th scope="row"> {{ $key + 1 }}
                        </th>
                        <th scope="row">{{ $salary->tin_num}}</th>

                        @if($pagibig)
                        <td scope="row"> {{ $salary->pagibig_num}} </td>
                        @endif

                        @if($sss)
                        <td scope="row"> {{ $salary->sss_num}} </td>
                        @endif

                        @if($philhealth)
                        <td scope="row"> {{ $salary->philhealth_num}} </td>
                        @endif

                        <td scope="row" class="text-left">{{ $salary->employee_name }}</td>
                        <td scope="row">{{ $salary->position }}</td>
                        <td scope="row"> {{ number_format($salary->smonthly_rate, 2) }} </td>
                        <td scope="row"> {{ number_format($salary->basic, 2) }} </td>
                        <td scope="row"> {{ $salary->day }} </td>
                        <td scope="row"> {{ $salary->hr }} </td>
                        <td scope="row"> {{ $salary->min }} </td>
                        <td scope="row"> {{ number_format($salary->deductions, 2) }} </td>
                        <td scope="row"> {{ number_format($salary->soa, 2) }} </td>
                        <th scope="row"> {{ number_format($salary->coop, 2) }} </th>
                        <th scope="row"> {{ number_format($salary->coop_loan, 2) }} </th>
                        <td scope="row"> {{ number_format($salary->tax, 2) }} </td>

                        @if($pagibig)
                        <td scope="row"> {{ number_format($salary->pagibig, 2) }} </td>
                        @endif

                        @if($sss)
                            <td scope=" row"> {{ number_format($salary->sss, 2) }} </td>
                        @endif

                        @if($philhealth)
                        <td scope="row"> {{ number_format($salary->philhealth, 2) }} </td>
                        @endif

                        @if($comm_allowance)
                        <td scope="row"> {{ number_format($salary->comm_allowance, 2) }} </td>
                        @endif
						
                        <th scope="row"> {{ number_format($salary->net_amt, 2) }} </th>
                        <td scope="row"> {{ $salary->lbp_num }} </td>
                        <td scope="row"> {{ $salary->remarks }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="22" class="text-center">No Salary records found!</td>
                    </tr>
                    @endforelse

                    @if($i < $pages) <div class="page-break">
    </div>
    @endif


    @if($i == $pages)
    <tr class="page-break">
        <th></th>
        <th></th>

        @if($pagibig)
        <th></th>
        @endif

        @if($sss)
        <th></th>
        @endif

        @if($philhealth)
        <th></th>
        @endif

        <th></th>
        <th class="text-right grandtotal" colspan="2">GRAND TOTAL</th>
        <th class="grandtotal">{{ number_format($salaries->sum('basic'), 2) }}</th>
        <th></th>
        <th></th>
        <th></th>
        <th class="grandtotal">{{ number_format($salaries->sum('deductions'), 2) }}</th>
        <th class="grandtotal">{{ number_format($salaries->sum('soa'), 2) }}</th>
        <th class="grandtotal">{{ number_format($salaries->sum('coop'), 2) }}</th>
        <th class="grandtotal">{{ number_format($salaries->sum('coop_loan'), 2) }}</th>
        <th class="grandtotal">{{ number_format($salaries->sum('tax'), 2) }}</th>


        @if($pagibig)
        <th class="grandtotal">{{ number_format($salaries->sum('pagibig'), 2) }}</th>
        @endif

        @if($sss)
        <th class="grandtotal">{{ number_format($salaries->sum('sss'), 2) }}</th>
        @endif

        @if($philhealth)
        <th class="grandtotal">{{ number_format($salaries->sum('philhealth'), 2) }}</th>
        @endif

        @if($comm_allowance)
         <td scope="row"> {{ number_format($salaries->sum('comm_allowance'), 2) }} </td>
        @endif

        <th class="grandtotal">{{ number_format($salaries->sum('net_amt'), 2) }}</th>
        <th></th>
        <th></th>
    </tr>
    @endif

    </tbody>

    </table>


    </div>
		@if($i < $pages) 
		<div class="page-break">
        </div>
        @endif
        @endfor

               <div class="row ">

            <div class="col-md-6 col-lg-6">
                <p class="statements">CERTIFIED: Services have been duly rendered as stated</p>
                <br>
                <p class="text-center">______________________________________________________</p>
                <p class="text-center"> {{ $chiefDiv->name }} <br> {{ $chiefDiv->position }} </p>
            </div>

            <div class="col-md-6 col-lg-6">
                <p class="statements">APPROVED FOR PAYMENT:</p>
                <br>
                <p class="text-center">______________________________________________________________</p>
                <p class="text-center"> {{ $rd->name }} <br> {{ $rd->position }} </p>
            </div>

        </div>
        <br>
        <div class="row">

            <div class="col-md-6 col-lg-6">
                <p class="statements">CERTIFIED: Funds available in the amount of PHP __<strong><u>{{number_format($salaries->sum('soa'), 2)}}</u></strong>___</p>
                <br>
                <p class="text-center">______________________________________________________</p>
                <p class="text-center"> {{ $accountant->name }} <br> {{ $accountant->position }} </p>
            </div>

            <div class="col-md-6 col-lg-6">
                <p class="statements">CERTIFIED: Each employee whose name appears above has been the amount indicated opposite his/her name.</p>
                <br>
                <p class="text-center">______________________________________________________</p>
                <p class="text-center"> {{ $cashier->name }} <br> {{ $cashier->position }} </p>
            </div>

        </div>
        </div>
</body>

</html>