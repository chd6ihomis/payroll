@extends('admin.master', ['activePage' => 'payrollsAll'])

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
                            <h4>Payroll Period: {{ $period }}</h4>
                        </span>
                        <span class="nav-item text-right">

                            @if(auth()->user()->role == '3')
							<a class="btn btn-primary" href="{{ route('printobr', [$period, $type]) }}" target="_blank">
                                <i class="fas fa-print" style="--fa-success-color: white"></i> Print ORS
                            </a>
							
							<a class="btn btn-danger" href="{{ route('printobr-conap', [$period, $type]) }}" target="_blank">
                                <i class="fas fa-print" style="--fa-success-color: white"></i> Print ORS Conap
                            </a>

                            <a class="btn btn-success" href="{{ route('printdv', [$period, $type]) }}" target="_blank">
                                <i class="fas fa-print" style="--fa-success-color: white"></i> Print DV
                            </a>
							
                            <a class="btn btn-info" href="{{ route('printdv-conap', [$period, $type]) }}" target="_blank">
                                <i class="fas fa-print" style="--fa-info-color: white"></i> Print DV Conap
                            </a>
							
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payrollPrint">
                                <i class="fas fa-print" style="--fa-primary-color: white"></i> Print Payroll
                            </button>

							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#cashiercopyPrint">
                                <i class="fas fa-print" style="--fa-success-color: white"></i> Cashier's Copy
                            </button>
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
                                    <th scope="col">Office</th>
                                    <th scope="col">Period</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Employees</th>
                                    <th scope="col">SOA</th>
                                    <th scope="col">SOA - CONAP</th>
                                    <th scope="col">Net Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">General Payroll for Contract of Service</td>
                                    <td class="text-center">{{ $period }}</td>
                                    <td class="text-center text-uppercase">{{ $type }}</td>
                                    <td class="text-center">{{ $total_employees->count() }}</td>
                                    <th class="text-center">{{ number_format($salaries->where('isCorrect', 'Y')->where('isConap', 'N')->sum('soa'), 2) }}</th>
                                    <th class="text-center">{{ number_format($salaries->where('isCorrect', 'Y')->where('isConap', 'Y')->sum('soa'), 2) }}</th>
                                    <th class="text-center">{{ number_format($salaries->where('isCorrect', 'Y')->sum('net_amt'), 2) }}</th>
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
                            <lead class="d-inline">Net Amount : {{ number_format($salaries->sum('net_amt'), 2)}}</lead>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table id="salarytbl" class="ui celled table table-bordered table-striped " style="100%">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Monthly Rate</th>
                                    <th scope="col">Computed</th>
                                    <th scope="col">Deductions</th>
                                    <th scope="col">SOA</th>
                                    <th scope="col" class="{{ $salaries->where('pagibig', '>', '0')->count() ? 'bg-success' : '' }}"> Pag-ibig</th>
                                    <th scope="col" class="{{ $salaries->where('sss', '>', '0')->count() ? 'bg-success' : '' }}">SSS</th>
                                    <th scope="col" class="{{ $salaries->where('philhealth', '>', '0')->count() ? 'bg-success' : '' }}">PhilHealth</th>
                                    <th scope="col" class="{{ $salaries->where('philhealth_otc', '>', '0')->count() ? 'bg-success' : '' }}">PhilHealth <br> OTC</th>
                                    <th scope="col" class="{{ $salaries->where('coop', '>', '0')->count() ? 'bg-success' : '' }}">Coop</th>
                                    <th scope="col" class="{{ $salaries->where('coop_loan', '>', '0')->count() ? 'bg-success' : '' }}">Coop <br> Loan</th>
                                    <th scope="col" class="{{ $salaries->where('comm_allowance', '>', '0')->count() ? 'bg-success' : '' }}">Comm <br> Allowance</th>
                                    <th scope="col">Net Amount</th>
                                    <th scope="col">Fund Source</th>
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
                                    <td class="text-center">{{ number_format($salary->pagibig, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->sss, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth_otc, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop_loan, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->comm_allowance, 2) }}</td>
                                    <th class="text-center">{{ number_format($salary->net_amt, 2) }}</th>
                                    <td class="text-center">{{ $salary->fund_source }}</td>
                                    <td class="text-center">{{ $salary->remarks }}</td>
                                </tr>
								 @empty
								<tr>
									<td colspan="18" class="text-center">No Salary records found!</td>
								</tr>
								@endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="nav float-right">
                        <span class="nav-item text-secondary p-1">
                            <em>* Only checked and correct salary will be generated</em>
                        </span>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-danger">NO SALARY PROCESSED ({{ $salaries_no_processed->count() }})</h5>
                </div>
                <div class="card-body">
                    <ul class="nav float-left">
                        <span>
                            @foreach($salaries_no_processed as $emp)
                            <li>{{ $emp->employee_name }} <br> <em class="text-secondary">( {{ \DB::table('office')->where('id', $emp->office)->first()->office_name }} )</em></li>
                            @endforeach
                        </span>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-primary">SALARIES FOR CHECKING ({{ $salaries_for_checking->count() }})</h5>
                </div>
                <div class="card-body">
                    <ul class="nav float-left">
                        <span>
                            @foreach($salaries_for_checking as $emp)
                            <li>{{ $emp->employee_name }}</li>
                            @endforeach
                        </span>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-warning">SALARIES FOR CORRECTION ({{ $salaries_for_correction->count() }})</h5>
                </div>
                <div class="card-body">
                    <ul class="nav float-left">
                        <span>
                            @foreach($salaries_for_correction as $emp)
                            <li>{{ $emp->employee_name }}</li>
                            @endforeach
                        </span>
                    </ul>
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
                <form action="{{ route('printpayroll') }}" method="post" target="_blank">

                    @csrf

                    <input type="text" hidden class="form-control col-6" name="period" id="period" placeholder="" value="{{ $period }}">
                    <input type="text" hidden class="form-control col-6" name="type" id="type" placeholder="" value="{{ $type }}">
					
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

                    <div class="form-group">
                        <label for="comm_allowance" class="form-check-label col-6 text-right">
                            <h5> Communication Allowance </h5>
                        </label>
                        <input class="form-check-input" type="checkbox" name="comm_allowance" data-bootstrap-switch>
                    </div>
					
					<div class="form-group">
                        <label for="isconap" class="form-check-label col-6 text-right">
                            <h5> Conap Only </h5>
                        </label>
                        <input class="form-check-input" type="checkbox" name="isconap" data-bootstrap-switch>
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
</div>

	<div class="modal fade" id="cashiercopyPrint">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-success">
						<i class="fas fa-cog"></i>
						Cashier's Copy Settings
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body justify-content-between">
					<form action="{{ route('cashiercopy') }}" method="post" target="_blank">

						@csrf

						<input type="text" hidden class="form-control col-6" name="period" id="period" placeholder="" value="{{ $period }}">
						<input type="text" hidden class="form-control col-6" name="type" id="type" placeholder="" value="{{ $type }}">
						
						<div class="form-group">
							<label for="isconap" class="form-check-label col-6 text-right">
								<h5> Conap Only </h5>
							</label>
							<input class="form-check-input" type="checkbox" name="isconap" data-bootstrap-switch>
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