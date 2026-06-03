<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>OBLIGATION REQUEST AND STATUS </title>

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
            width: 250px;
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
                <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" style="opacity: .8; width:100px;height:100px; position:absolute; left:20px; top:50px; z-index:1">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="table">
                <!-- HEADER -->
                <table class="table">
                    <tr>
                        <th class="text-center">
                            <label> OBLIGATION REQUEST AND STATUS <br> DEPARTMENT OF HEALTH <br> WESTERN VISAYAS <br> CENTER FOR HEALTH DEVELOPMENT</label>
                        </th>
                        <td class="text-left" style="width:400px">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">No: 02-101101-{{ now()->format('Y')}}</li>
                                <li class="list-group-item">Date: </li>
                                <li class="list-group-item">Fund:</li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <table class="table">
                    <tr>
                        <td class="text-center" style="width:300px">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Payee</li>
                                <li class="list-group-item">Office</li>
                                <li class="list-group-item">Address</li>
                            </ul>
                        </td>
                        <th class="text-left">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">DOH CHD WV</li>
                                <li class="list-group-item">N/A</li>
                                <li class="list-group-item">Bolong Oeste, Sta. Barbara</li>
                            </ul>
                        </th>
                    </tr>
                </table>
                <!-- HEADER -->

                <!-- CONTENT -->
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Responsibility <br> Center</th>
                            <th>Particulars</th>
                            <th>MFO/PAP</th>
                            <th>UACS Code/Expenditure</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr colspan="1">
                            <td class="text-center">
                                <ul class="list-group list-group-flush">
                                    @forelse($salaries->sortBy('fund_source') as $office)
										@if(substr($office->fund_source, 0, 3) == 'SAA')
										<li class="list-group-item"> {{ substr($office->fund_source,4,17) }}</li>
										@else
										<li class="list-group-item"> {{ $office->office }}</li>
										@endif
                                    @empty
                                    <li class="list-group-item"></li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="text-center" style="width:250px">
                                <p> To payment of services rendered by Contract of Service (COS) of {{ \DB::table('office')->where('id', auth()->user()->office)->first()->division }} for the period covering {{ $period }}. {{ $desc }}</p>
                            </td>
                            <td class="text-center" style="width:250px">
                                <ul class="list-group list-group-flush">
                                    @forelse($salaries->sortBy('fund_source') as $mfo)
                                    <li class="list-group-item"> {{ \DB::table('fund_source')->where('desc', $mfo->fund_source)->first()->mfo_pap }}</li>
                                    @empty
                                    <li class="list-group-item"></li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="text-center" style="width:150px">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">5021199000</li>
                                </ul>
                            </td>
                            <td class="text-center" style="width:150px">
                                <ul class="list-group list-group-flush" >
                                    @forelse($salaries->sortBy('fund_source') as $amt)
                                    <li class="list-group-item"> {{ number_format($amt->total,2) }} </li>
                                    @empty
                                    <li class="list-group-item"> </li>
                                    @endforelse
                                </ul>
                            </td>
                        </tr>
                        <tr class="total">
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-center">{{ number_format($salaries->sum('total'),2) }}</th>
                        </tr>
                    </tbody>

                </table>
                <!-- CONTENT -->

                <!-- SIGNATURES -->
                <table class="table">
                    <tr class="signatures" rowspan="2">
                        <th class="text-center" style="width:100px">
                            A.
                        </th>
                        <td class="text-left">
                            <p>Certified: Charges to appropriate/allotment necessary, lawful and under my direct supervision; and supporting documents valid, proper and legal.</p>
                        </td>
                        <th class="text-center" style="width:100px">
                            B.
                        </th>
                        <td class="text-left">
                            <p>Certified: Allotment available and obligated for the purpose/adjustment necessary as indicated above.</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" class="text-left">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Signature:</li>
                                <li class="list-group-item">Name:</li>
                                <li class="list-group-item">Position:</li>
                                <li class="list-group-item">Date:</li>
                            </ul>
                        </td>
                        <th colspan="1" class="text-center">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><input></input></li>
                                <li class="list-group-item"> {{ $chiefDiv->name }} </li>
                                <li class="list-group-item"> {{ $chiefDiv->position }} </li>
                                <li class="list-group-item"></li>
                            </ul>
                        </th>
                        <td colspan="1" class="text-left">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Signature:</li>
                                <li class="list-group-item">Name:</li>
                                <li class="list-group-item">Position:</li>
                                <li class="list-group-item">Date:</li>
                            </ul>
                        </td>
                        <th colspan="1" class="text-center">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><input></input></li>
                                <li class="list-group-item"> {{ $budget->name }} </li>
                                <li class="list-group-item"> {{ $budget->position }} </li>
                                <li class="list-group-item"></li>
                            </ul>
                        </th>
                    </tr>
                </table>
                <!-- SIGNATURES -->

                <!-- STATUS -->
                <table class="table" style="border:1px solid black">
                    <tr>
                        <th colspan="1" class="text-center">
                            C.
                        </th>
                        <th colspan="6" class="text-center">
                            STATUS OF OBLIGATION
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center">
                            Reference
                        </th>
                        <th colspan="4" class="text-center">
                            Amount
                        </th>
                    </tr>
                    <tr>
                        <td class="text-center">Date</td>
                        <td class="text-center">Particulars</td>
                        <td class="text-center">ORS/JEV/RCI/RADAI No.</td>
                        <td class="text-center">Obligation</td>
                        <td class="text-center">Payment</td>
                        <td class="text-center">Not Yet Due</td>
                        <td class="text-center">Due and Demandable</td>
                    </tr>
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center">OBLIGATION</th>
                        <th class="text-center">02-101101-{{ now()->format('Y')}}-</th>
                        <th class="text-center">{{ number_format($salaries->sum('total'),2) }}</th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                        <th class="text-center">{{ number_format($salaries->sum('total'),2) }}</th>
                    </tr>
                    <tr class="total">
                        <th class="text-right" colspan="3">Total</th>
                        <th class="text-center">{{ number_format($salaries->sum('total'),2) }}</th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                    </tr>

                </table>
                <!-- STATUS -->
            </div>
        </div>


    </div>
</body>

</html>