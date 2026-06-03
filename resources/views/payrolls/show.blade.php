@extends('admin.master', ['activePage' => 'payrolls'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payrolls Management</a></li>
                    <li class="breadcrumb-item active">Payroll</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @auth
                    <ul class="nav justify-content-between">
                        <span class="nav-item text-left">
                            <h4>Payroll no. {{ $payroll->id }}</h4>
                        </span>
                        <span class="nav-item text-right">
                            @if(auth()->user()->office != '1')
                            <form class="d-inline" action="{{ route('payrolls.destroy', $payroll->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-danger" onclick="confirm('{{ __("Delete Payroll?") }}') ? this.parentElement.submit() : '' ">
                                    <i class="fas fa-trash" style="--fa-primary-color: white"></i>
                                </button>
                            </form>
                            @endif

                            @if(auth()->user()->office == '1')
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payrollPrint">
                                <i class="fas fa-print" style="--fa-primary-color: white"></i>
                            </button>

                            <a class="btn btn-success" href="{{ route('cashiercopy', $payroll->id) }}">
                                <i class="fas fa-print" style="--fa-success-color: white"></i>
                            </a>
                            @endif

                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table table-responsive">
                        <table class="ui celled table table-bordered" style="width:100%;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">Date Created</th>
                                    <th scope="col">Office</th>
                                    <th scope="col">Period</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($payroll->created_at)->toFormattedDateString() }}</td>
                                    <td class="text-center">{{ \DB::table('office')->where('id', $payroll->office)->first()->office_name }}</td>
                                    <td class="text-center">{{ $payroll->period }}</td>
                                    <td class="text-center text-uppercase">{{ $payroll->type }}</td>
                                    <th class="text-center">{{ number_format(\App\Salary::where('payroll_id', $payroll->id)->sum('net_amt'), 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @auth
                    <ul class="nav justify-content-between">
                        <span class="nav-item text-left">
                            <h5>Salaries Summary</h5>
                        </span>
                        <span class="nav-item text-right">
                            <lead class="d-inline">Sub-Total : {{ number_format($salaries->sum('net_amt'), 2)}}</lead>
                            <a class="btn btn-success" href="{{ route('salaries.show', $payroll->id) }}" data-toggle="tooltip" data-placement="top" title="View"><i class="fas fa-eye"></i></a>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        {{ $salaries->links() }}

                        <table id="salarytbl" class="ui celled table table-bordered table-striped " style="width:100%;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Monthly Rate</th>
                                    <th scope="col">Computed</th>
                                    <th scope="col">Deductions</th>
                                    <th scope="col">SOA</th>
                                    <th scope="col">Pag-ibig</th>
                                    <th scope="col">SSS</th>
                                    <th scope="col">PhilHealth</th>
                                    <th scope="col">Net Amount</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($salaries as $key => $salary)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-left">{{ $salary->employee_name }}</td>
                                    <td class="text-center">{{ $salary->position }}</td>
                                    <td class="text-center">{{ number_format($salary->smonthly_rate, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->basic, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->deductions, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->soa, 2) }}</td>
                                    <td class="text-center {{ $salary->pagibig == 0 ? '' : 'bg-success' }}">{{ number_format($salary->pagibig, 2) }}</td>
                                    <td class="text-center {{ $salary->sss == 0 ? '' : 'bg-success' }}">{{ number_format($salary->sss, 2) }}</td>
                                    <td class="text-center {{ $salary->philhealth == 0 ? '' : 'bg-success' }}">{{ number_format($salary->philhealth, 2) }}</td>
                                    <th class="text-center">{{ number_format($salary->net_amt, 2) }}</th>
                                    <td class="text-center">{{ $salary->remarks }}</td>
                                </tr>
                            </tbody>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">No Salary records found!</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="payrollPrint">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-cog"></i>
                    Payroll Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body justify-content-between">
                <form action="{{ route('printpayroll') }}" method="post">

                    @csrf

                    <input type="text" hidden class="form-control col-6" name="payroll_id" id="payroll_id" placeholder="" value="{{ $payroll->id }}">
                    <div class="form-group">
                        <label for="pagibig" class="form-check-label col-6 text-right">
                            <h5> Pag-Ibig </h5>
                        </label>
                        <input class="form-check-input" type="checkbox" name="pagibig" checked data-bootstrap-switch>
                    </div>


                    <div class="form-group">
                        <label for="sss" class="form-check-label col-6 text-right">
                            <h5> SSS </h5>
                        </label>
                        <input class="form-check-input" type="checkbox" name="sss" checked data-bootstrap-switch>
                    </div>

                    <div class="form-group">
                        <label for="philhealth" class="form-check-label col-6 text-right">
                            <h5> PhilHealth </h5>
                        </label>
                        <input class="form-check-input" type="checkbox" name="philhealth" checked data-bootstrap-switch>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Generate</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

@endsection