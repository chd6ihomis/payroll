<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>DISBURSEMENT VOUCHER</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/plugin/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        @media print {
            .page-break {
                page-break-after: always
            }
        }

        @page {
            size: auto;
        }

        table {
            font-family: "Arial Narrow", Arial, sans-serif;
            font-size: 15px;
            letter-spacing: 0.12em;
        }
		
		table, th, td {
		  border: 1px solid;
		  border-top: 1px solid;
		}

        .total {
            font-size: 18px;
        }

        .signatures {
            font-size: 12px;
        }

        input {
            width: 350px;
            border: 0;
            outline: 0;
            background: transparent;
            border-bottom: 1px solid black;
        }
		
		.list-group-item {
			border-bottom: 1px solid;
		}
    </style>
</head>

<body style="background: white">

    <div class="container-fluid">

        <div class="row">
            <div class="col text-center">
                <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" style="opacity: .8; width:100px;height:100px; position:absolute; left:20px; top:60px; z-index:1">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="table">
                <!-- HEADER -->
                <table class="table">
                    <tr>
                        <th class="text-center">
                            <label> DEPARTMENT OF HEALTH <br> WESTERN VISAYAS <br> CENTER FOR HEALTH DEVELOPMENT <br> Bolong Oeste, Sta Barbara</label>
                            <h4>DISBURSEMENT VOUCHER</h4>
                        </th>
                        <td class="text-left">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Fund Cluster:</li>
                                <li class="list-group-item">Date:</li>
                                <li class="list-group-item">DV No:</li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <table class="table">
                    <tr class="text-center total">
                        <th>Mode of <br> Payment</th>
                        <td><i class="far fa-square"></i> MDS Check</td>
                        <td><i class="far fa-square"></i> Commercial Check</td>
                        <td><i class="far fa-square"></i> ADA</td>
                        <td><i class="far fa-square"></i> Others (Please specify)</td>
                    </tr>
                    <tr>
                        <th>Payee</th>
                        <th colspan="2"> DOH CHD WV</th>
                        <td rowspan="2"> TIN/Employee No:</td>
                        <td rowspan="2"> ORS/BURS No: </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th colspan="2"> Bolong Oeste, Sta Barbara </th>
                    </tr>
                </table>
                <!-- HEADER -->

                <!-- CONTENT -->
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Particulars</th>
                            <th>Responsibility</th>
                            <th>MFO/PAP</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tr colspan="1">
                        <td class="text-center" style="width:450px">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> To payment of services rendered by Contract of Service (COS) of {{ \DB::table('office')->where('id', auth()->user()->office)->first()->division }} for the period covering {{ $period }}. {{ $desc }} </li>
                            </ul>

                        </td>
                        <td rowspan="2" class="text-center">
                            <ul class="list-group list-group-flush">
                                @forelse($salaries->sortBy('fund_source') as $fs)
                                <li class="list-group-item">{{ $fs->fund_source }}</li>
                                @empty
                                <li class="list-group-item"></li>
                                @endforelse
                            </ul>
                        </td>
                        <td rowspan="2" class="text-center " style="width:200px">
                            <ul class="list-group list-group-flush">
                                @forelse($salaries->sortBy('fund_source') as $fs)
                                <li class="list-group-item">{{ \DB::table('fund_source')->where('desc', $fs->fund_source)->first()->mfo_pap }}</li>
                                @empty
                                <li class="list-group-item"></li>
                                @endforelse
                            </ul>
                        </td>
                        <td rowspan="2" class="text-center">
                            <ul class="list-group list-group-flush">
                                @forelse($salaries->sortBy('fund_source') as $fs)
                                <li class="list-group-item">{{ number_format($fs->net, 2) }}</li>
                                @empty
                                <li class="list-group-item"></li>
                                @endforelse
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"> Basic Salary <span class="float-right">{{ number_format($salaries->sum('basic'),2) }}</span></li>
                                <li class="list-group-item"> Less: Undertime/Late/Absent <span class="float-right">{{ number_format($salaries->sum('deductions'),2) }}</span></li>
                                <li class="list-group-item"> Add: Comm. Allowance <span class="float-right">{{ number_format($salaries->sum('comm_allowance'),2) }}</span></li>
                                <li class="list-group-item font-weight-bold"> Gross Amt. <span class="float-right">{{ number_format($salaries->sum('soa'),2) }}</span></li>
                                <li class="list-group-item"> Less Tax <span class="float-right">{{ number_format($salaries->sum('tax'),2) }}</span></li>
                                <li class="list-group-item"> Less Pag-Ibig <span class="float-right">{{ number_format($salaries->sum('pagibig'),2) }}</span></li>
                                <li class="list-group-item"> Less SSS <span class="float-right">{{ number_format($salaries->sum('sss'),2) }}</span></li>
                                <li class="list-group-item"> Less Philhealth <span class="float-right">{{ number_format($salaries->sum('philhealth'),2) }}</span></li>
                                <li class="list-group-item"> Less Coop <span class="float-right">{{ number_format($salaries->sum('coop'),2) }}</span></li>
                                <li class="list-group-item"> Less Coop Loan <span class="float-right">{{ number_format($salaries->sum('coop_loan'),2) }}</span></li>

                            </ul>
                        </td>
                    </tr>
                    <tr class="total">
                        <th class="text-right">Amount Due: {{ number_format($salaries->sum('net'),2) }}</th>
                        <th colspan="3" class="text-right">{{ number_format($salaries->sum('net'),2) }}</th>
                    </tr>
                </table>
                <!-- CONTENT -->

                <!-- SIGNATURES -->
                <table class="table">
                    <tr class="signatures">
                        <th class="text-center">
                            A.
                        </th>
                        <td class="text-left">
                            <p>Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item font-weight-bold"> {{ $chiefDiv->name }} <br> {{ $chiefDiv->position }} </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                <!-- SIGNATURES -->

                <!-- Accounting Entry -->
                <table class="table" style="font-size: 14px">
                    <tr class="text-center">
                        <th class="text-left">
                            B.
                        </th>
                        <th colspan="3">
                            Accounting Entry
                        </th>
                    </tr>
                    <tr class="text-center">
                        <th style="width:350px">Account Title</th>
                        <th>UACS Code</th>
                        <th>Debit</th>
                        <th>Credit</th>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Other Professional Services</td>
                        <td>5021199000</td>
                        <td>{{ number_format($salaries->sum('soa'),2) }}</td>
                        <td></td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Telephone Expenses - Mobile </td>
                        <td>5020502001</td>
                        <td>{{ number_format($salaries->sum('comm_allowance'),2) }}</td>
                        <td></td>
                    </tr>
                    <tr class="text-center">
                        <td>Cash, MDS, Regular</td>
                        <td>1010404000</td>
                        <td></td>
                        <td>{{ number_format($salaries->sum('net'),2) }}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Due to BIR</td>
                        <td>2020101000</td>
                        <td></td>
                        <td>{{ number_format($salaries->sum('tax'),2) }}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Due to Pag-Ibig</td>
                        <td>2020103001</td>
                        <td></td>
                        <td>{{ number_format($salaries->sum('pagibig'),2) }}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Due to PhilHealth</td>
                        <td>2020104000</td>
                        <td></td>
                        <td>{{ number_format($salaries->sum('philhealth'),2) }}</td>
                    </tr>
                    <tr class="text-center">
                        <td>Other Payables (SSS, Coop Cont. & Loan)</td>
                        <td>2999999000</td>
                        <td></td>
                        <td>{{ number_format(($salaries->sum('sss') + $salaries->sum('coop') + $salaries->sum('coop_loan')),2)}}</td>
                    </tr>
                    <tr class="text-center">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="text-center">
                        <th class="text-right">TOTAL</th>
                        <th></th>
                        <th>{{ number_format(($salaries->sum('soa') + $salaries->sum('comm_allowance')),2) }}</th>
                        <th>{{ number_format(($salaries->sum('net') + $salaries->sum('tax') + $salaries->sum('pagibig') + $salaries->sum('sss') + $salaries->sum('coop') + $salaries->sum('coop_loan') + $salaries->sum('philhealth')),2) }}</th>
                    </tr>
                </table>
                <!-- Accounting Entry -->

                <!-- SIGNATURES -->
                <table class="table">
                    <tr class="text-center">
                        <th colspan="2" class="text-left">C. Certified:</th>
                        <th colspan="2" class="text-left">D.</th>
                    </tr>
                    <tr class="text-left">
                        <td colspan="2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="far fa-square"></i> Cash Available</li>
                                <li class="list-group-item"><i class="far fa-square"></i> Subject to Authority to Debit Account (when applicable)</li>
                                <li class="list-group-item"><i class="far fa-square"></i> Supporting documents complete and amount claimed </li>
                            </ul>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="text-center">
                        <td>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> Signature</li>
                                <li class="list-group-item"> Name</li>
                                <li class="list-group-item"> Position </li>
                                <li class="list-group-item"> Date </li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> </li>
                                <li class="list-group-item font-weight-bold"> {{ $accountant->name }} </li>
                                <li class="list-group-item"> Accountant III <br> Head, Accounting Unit/Authorized Representative </li>
                                <li class="list-group-item"> </li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> Signature</li>
                                <li class="list-group-item"> Name</li>
                                <li class="list-group-item"> Position </li>
                                <li class="list-group-item"> Date </li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> </li>
                                <li class="list-group-item font-weight-bold"> {{ $rd->name }}  </li>
                                <li class="list-group-item"> Director IV <br> Agency Head/Authorized Representative </li>
                                <li class="list-group-item"> </li>
                            </ul>
                        </td>

                    </tr>
                </table>
                <!-- SIGNATURES -->

                <!-- RECEIPT OF PAYMENT -->
                <table class="table">
                    <tr class="text-center">
                        <th>E.</th>
                        <th colspan="3">Receipt of Payment</th>
                        <th rowspan="2" class="text-left">JEV No:</th>
                    </tr>
                    <tr>
                        <td>Check/ <br> ADA No:</td>
                        <td class="text-center"><input type="text" style="width:200px"></td>
                        <td>Date: </td>
                        <td>Bank Name & Account Number:</td>
                    </tr>
                    <tr>
                        <td>Signature: </td>
                        <td class="text-center"><input type="text" style="width:200px"></td>
                        <td>Date: </td>
                        <td>Printed Name:</td>
                        <td rowspan="2">Date</td>
                    </tr>
                    <tr>
                        <td colspan="4">Official Receipt No. & Date/Other Documents</td>
                    </tr>
                </table>
                <!-- RECEIPT OF PAYMENT -->
            </div>
        </div>


    </div>
</body>

</html>