@extends('admin.master', ['activePage' => 'reports'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark"></h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    @auth
                    <ul class="nav justify-content-between">
                        <span class="nav-item text-left">
                            <h5>Salary Utilization</h5>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table id="employeetbl" class="ui celled table table-bordered" style="width:100%; font-size:14px;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">Period</th>
                                    <th scope="col">Fund Source</th>
                                    <th scope="col">Amount (SOA)</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach($periods as $period)
                                <tr>
                                    <td class="text-center font-weight-bold" rowspan="{{ \App\Salary::where('office', DB::table('office')
                                    ->where('id', auth()->user()->office)->first()->shortname)->where('isCorrect', 'Y')
                                    ->whereDate('payroll_date', $period->payroll_date)->groupBy('fund_source')
                                    ->select(DB::raw("SUM(`soa`) AS `quantity_sum`"), 'fund_source')
                                    ->get()->count() + 1 }}"> {{ \Carbon\Carbon::parse($period->payroll_date)->format('M Y') }}</td>

                                    @foreach( $fund_sources = \App\Salary::where('office', DB::table('office')
                                    ->where('id', auth()->user()->office)->first()->shortname)->where('isCorrect', 'Y')
                                    ->whereDate('payroll_date', $period->payroll_date)->groupBy('fund_source')
                                    ->select(DB::raw("SUM(`soa`) AS `quantity_sum`"), 'fund_source')
                                    ->get() as $fund_source )
                                    <tr>
                                        <td class="text-center"> {{ $fund_source->fund_source }}</td>
                                        <td class="text-right"> {{ number_format($fund_source->quantity_sum, 2) }}</td>
                                    </tr>
                                    @endforeach

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection