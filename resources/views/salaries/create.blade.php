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
                    <li class="breadcrumb-item"><a href="{{ route('payrolls.show', $payroll->id) }}">Payroll</a></li>
                    </li>
                    <li class="breadcrumb-item active">Salary Management</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Form -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @auth
                    <ul class="nav justify-content-between">
                        <span class="nav-item text-left">
                            <h4>Payroll Details</h4>
                        </span>
                        <span class="nav-item text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#salaryAdd"> Add Entry </button>
                        </span>
                    </ul>
                    @endauth
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered col-md-6">
                        <tbody>
                            <tr>
                                <th class="text-right text-success">Office: </th>
                                <td class="text-left">{{ \DB::table('office')->where('id', $payroll->office)->first()->office_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-right text-success">Period: </th>
                                <td class="text-left">{{ $payroll->period }}</td>
                            </tr>
                            <tr>
                                <th class="text-right text-success">Type: </th>
                                <td class="text-left text-uppercase">{{ $payroll->type }}</td>
                            </tr>
                            <tr>
                                <th class="text-right text-success">Amount: </th>
                                <td class="text-left">{{ number_format($salaries->sum('net_amt'), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @auth
                    <ul class="nav justify-content-between">
                        <span class="nav-item text-left">
                            <h5>Salary Details</h5>
                        </span>

                        <span class="nav-item text-right">
                            <lead>Total Entries: {{ $salaries->count() }}</lead>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table class="ui celled table table-bordered table-striped" style="width:100%; font-size:11px">
                            <thead class="text-center text-success">
                                <tr>
                                    <th rowspan="2"></th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Monthly <br> Rate</th>
                                    <th rowspan="2">{{ $payroll->period }}</th>
                                    <th rowspan="1" colspan="3">Absences/Late <br> Undertime</th>
                                    <th rowspan="2">Total</th>
                                    <th rowspan="2">SOA</th>
                                    <th rowspan="2">Tax</th>
                                    <th rowspan="2">Pag-Ibig</th>
                                    <th rowspan="2">SSS</th>
                                    <th rowspan="2">PhilHealth</th>
                                    <th rowspan="2">PhilHealth <br> OTC</th>
                                    <th rowspan="2">Coop</th>
                                    <th rowspan="2">Coop <br> Loan</th>
                                    <th rowspan="2">Comm <br> Allowance</th>
                                    <th rowspan="2">Net <br> Amount</th>
                                    <th rowspan="2">Fund Source</th>
                                    <th rowspan="2">Remarks</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th>Day</th>
                                    <th>Hrs</th>
                                    <th>Mins</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                <tr>
                                    @switch($salary->isCorrect)
                                    @case('Y')
                                    <td class="text-center bg-success" style="font-size:12px">{{ $salary->calculation }}</td>
                                    @break
                                    @case('N')
                                    <td class="text-center bg-danger" style="font-size:12px"> {{ $salary->calculation }} </td>
                                    @break
                                    @default
                                    <td class="text-center bg-secondary" style="font-size:12px"> {{ $salary->calculation }} </td>
                                    @endswitch
                                    <td class="text-center" style="font-size:12px">{{ $salary->employee_name }}</td>
                                    <td class="text-center">{{ number_format($salary->smonthly_rate, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->basic, 2) }}</td>
                                    <td class="text-center">{{ $salary->day }}</td>
                                    <td class="text-center">{{ $salary->hr }}</td>
                                    <td class="text-center">{{ $salary->min }}</td>
                                    <td class="text-center">{{ number_format($salary->deductions, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->soa, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->tax, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->pagibig, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->sss, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth_otc, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop_loan, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->comm_allowance, 2) }}</td>
                                    <th class="text-center">{{ number_format($salary->net_amt, 2) }}</th>
                                    <th class="text-center">{{ $salary->fund_source }}</th>
                                    <td class="text-center">{{ $salary->remarks }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('salaries.destroy', $salary->sid) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            @if($salary->isCorrect == 'D')
                                            <button type="button" id="editSalary" class="btn btn-success btn-sm" data-toggle="modal" data-target="#salaryEdit" data-id="{{ $salary->sid }}"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm" rel="tooltip" title="Delete" onclick="confirm('{{ __("Delete Entry?") }}') ? this.parentElement.submit() : ''"><i class="fas fa-trash-alt" style="--fa-primary-color: white"></i></button>
											@elseif($salary->isCorrect == 'N')
                                            <button type="button" id="editSalary" class="btn btn-success btn-sm" data-toggle="modal" data-target="#salaryEdit" data-id="{{ $salary->sid }}"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm" rel="tooltip" title="Delete" onclick="confirm('{{ __("Delete Entry?") }}') ? this.parentElement.submit() : ''"><i class="fas fa-trash-alt" style="--fa-primary-color: white"></i></button>
											@elseif($salary->isCorrect == 'Y')
                                            <button type="button" class="btn btn-danger btn-sm" disabled><i class="fas fa-ban"></i></button>
                                            @endif
											<button type="button" id="showNotifications" class="btn btn-info btn-sm" data-toggle="modal" data-target="#notifications" data-id="{{ $salary->sid }}"><i class="fab fa-facebook-messenger"></i></button>
											<a class="btn btn-success btn-sm" href="{{ route('printsoa', $salary->sid) }}" target="_blank" title="Print SOA"><i class="fas fa-print" style="--fa-primary-color: white"></i></a>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="20" class="text-center">No Salary records found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="nav float-left d-inline">
                        <span class="nav-item bg-secondary text-left p-1">
                            <em>Uncheck</em>
                        </span>
                        <span class="nav-item bg-success text-left p-1">
                            <em>Correct</em>
                        </span>
                        <span class="nav-item bg-danger text-left p-1">
                            <em>Incorrect</em>
                        </span>
                    </ul>
                    <ul class="nav float-right d-inline text-secondary ">
                        <span class="nav-item p-1">
                            <small><em>Calculations: </em></small>
                        </span>
                        <span class="nav-item p-1">
                            <small><em>1 - SC Half</em></small>
                        </span>
                        <span class="nav-item p-1">
                            <small><em>2 - SC Month</em></small>
                        </span>
                        <span class="nav-item p-1">
                            <small><em>3 - IS Month</em></small>
                        </span>
                        <span class="nav-item p-1">
                            <small><em>4 - IS Half</em></small>
                        </span>
                        <span class="nav-item p-1">
                            <small><em>5 - Custom 1</em></small>
                        </span>
                        <span class="nav-item p-1">
                            <small><em>6 - Custom 2</em></small>
                        </span>
						<span class="nav-item p-1">
                            <small><em>7 - LS Half</em></small>
                        </span>
						<span class="nav-item p-1">
                            <small><em>8 - LS Month</em></small>
                        </span>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="salaryAdd">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    New Entry
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('salaries.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="leaveDate">Select Computation</label>
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios1" value="1" checked>
                                <label class="form-check-label" for="exampleRadios1">
                                    Standard Computation (Half)
                                </label>
                            </div>
                        </div>

                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios2" value="2">
                                <label class="form-check-label" for="exampleRadios2">
                                    Standard Computation (Whole Month)
                                </label>
                            </div>
                        </div>
						
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios7" value="7">
                                <label class="form-check-label" for="exampleRadios7">
                                    Last Salary (Half Month)
                                </label>
                            </div>
                        </div>

                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios8" value="8">
                                <label class="form-check-label" for="exampleRadios8">
                                    Last Salary (Whole Month)
                                </label>
                            </div>
                        </div>

                        @if($detect_month != 12)
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios3" value="3">
                                <label class="form-check-label" for="exampleRadios3">
                                    Initial Salary (Whole Month / 2nd Half)
                                </label>
                            </div>
                        </div>
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios4" value="4">
                                <label class="form-check-label" for="exampleRadios4">
                                    Initial Salary (1st Half)
                                </label>
                            </div>
                        </div>
						
						<div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios11" value="11" onclick="document.getElementById('working_days').readOnly=false">
                                <label class="form-check-label" for="exampleRadios11">
                                    By Working Days
                                </label>
                            </div>
                        </div>
						
						<div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios9" value="9">
                                <label class="form-check-label" for="exampleRadios9">
                                    Custom (Oct. 16-27)
                                </label>
                            </div>
                        </div>
						
						<div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios10" value="10">
                                <label class="form-check-label" for="exampleRadios10">
                                    Custom (Oct. 28-31)
                                </label>
                            </div>
                        </div>
						
                        @elseif($detect_month == 12)
						<div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios3" value="3">
                                <label class="form-check-label" for="exampleRadios3">
                                    Initial Salary (Whole Month / 2nd Half)
                                </label>
                            </div>
                        </div>
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios4" value="4">
                                <label class="form-check-label" for="exampleRadios4">
                                    Initial Salary (1st Half)
                                </label>
                            </div>
                        </div>
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios5" value="5">
                                <label class="form-check-label" for="exampleRadios5">
                                    Custom (Dec. 16-25)
                                </label>
                            </div>
                        </div>
                        <div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios6" value="6">
                                <label class="form-check-label" for="exampleRadios6">
                                    Custom (Dec. 26-31)
                                </label>
                            </div>
                        </div>
						
						<div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios13" value="13">
                                <label class="form-check-label" for="exampleRadios13">
                                    Custom (Dec. 1-25)
                                </label>
                            </div>
                        </div>
						
						<div class="form-check">
                            <div class="icheck-success">
                                <input class="form-check-input" type="radio" name="salaryComputation" id="exampleRadios12" value="12">
                                <label class="form-check-label" for="exampleRadios12">
                                    Period Based Formula
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="employeeInput">Employee</label>
                        <select class="form-control form-control-sm select2" name="employee_id" id="employeeInput">
                            <option selected disabled>{{ 'Select Employee' }}</option>
                            @forelse($employees = \App\Employee::where('office', auth()->user()->office)->where('status', 'true')->get() as $employee)
                            <option value="{{$employee->id}}">{{ $employee->employee_name }}</option>
                            @empty
                            <option>"No employee Found"</option>
                            @endforelse
                        </select>
                    </div>
					
					<div class="form-group">
                        <label for="working_days">No. of Working Days</label>
                        <input type="text" class="form-control col-6" name="working_days" id="working_days" value="22" readonly>
                    </div>
					
                    <div class="form-group">
                        <label for="day">Day</label>
                        <input type="text" class="form-control col-6" name="day" id="day" value="0">
                    </div>
                    <div class="form-group">
                        <label for="hrs">Hours</label>
                        <input type="text" class="form-control col-6" name="hrs" id="hrs" value="0">
                    </div>
                    <div class="form-group">
                        <label for="mins">Minutes</label>
                        <input type="text" class="form-control col-6" name="mins" id="mins" value="0">
                    </div>

                    <div class="form-group">
                        <label for="tax">Tax</label>
                        <input type="text" class="form-control col-6" name="tax" id="tax" value="0">
                    </div>

                    <div class="form-group">
                        <label for="pagibig">Pag-Ibig</label>
                        <input type="text" class="form-control col-6" name="pagibig" id="pagibig" value="0">
                    </div>

                    <div class="form-group">
                        <label for="sss">SSS</label>
                        <input type="text" class="form-control col-6" name="sss" id="sss" value="0">
                    </div>

                    <div class="form-group">
                        <label for="philhealth_otc">PhilHealth Over-the-Counter (OTC)</label>
                        <input type="text" class="form-control col-6" name="philhealth_otc" id="philhealth_otc" value="0">
                    </div>
					
					<div class="form-group">
                        <label for="coop">Coop</label>
                        <input type="text" class="form-control col-6" name="coop" id="coop" value="0">
                    </div>

                    <div class="form-group">
                        <label for="coop_loan">Coop Loan</label>
                        <input type="text" class="form-control col-6" name="coop_loan" id="coop_loan" value="0">
                    </div>

                    <div class="form-group">
                        <label for="comm_allowance">Communication Allowance</label>
                        <input type="text" class="form-control col-6" name="comm_allowance" id="comm_allowance" value="0">
                    </div>

                    <input type="hidden" class="form-control col-6" name="payroll_id" id="payroll_id" value="{{ $payroll->id }}">
                    <input type="hidden" class="form-control col-6" name="period" id="period" value="{{ $payroll->period }}">
                    <input type="hidden" class="form-control col-6" name="payroll_type" id="payroll_type" value="{{ $payroll->type }}">

                    <div class="justify-content-between">
                        <button type="submit" class="btn btn-success">Compute</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

@isset($salary->id)
<div class="modal fade" id="salaryEdit">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    Edit Entry
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('salaries.update', $salary->id) }}" method="post">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="employeeInput">Employee</label>
                        <input type="text" class="form-control-plaintext col" id="employee" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="working_days">No. of Working Days</label>
                        <input type="text" readonly class="form-control col-6" name="working_days" id="eworking_days" value="0">
                    </div>
                    <div class="form-group">
                        <label for="day">Day</label>
                        <input type="text" class="form-control col-6" name="day" id="eday" value="0">
                    </div>
                    <div class="form-group">
                        <label for="hrs">Hours</label>
                        <input type="text" class="form-control col-6" name="hr" id="ehr" value="0">
                    </div>
                    <div class="form-group">
                        <label for="mins">Minutes</label>
                        <input type="text" class="form-control col-6" name="min" id="emin" value="0">
                    </div>

                    <div class="form-group">
                        <label for="tax">Tax</label>
                        <input type="text" class="form-control col-6" name="tax" id="etax" value="0">
                    </div>

                    <div class="form-group">
                        <label for="pagibig">Pag-Ibig</label>
                        <input type="text" class="form-control col-6" name="pagibig" id="epagibig" value="0">
                    </div>

                    <div class="form-group">
                        <label for="sss">SSS</label>
                        <input type="text" class="form-control col-6" name="sss" id="esss" value="0">
                    </div>

                    <div class="form-group">
                        <label for="philhealth_otc">PhilHealth Over-to-Counter (OTC)</label>
                        <input type="text" class="form-control col-6" name="philhealth_otc" id="ephilhealth_otc" value="0">
                    </div>

					<div class="form-group">
                        <label for="coop">Coop</label>
                        <input type="text" class="form-control col-6" name="coop" id="ecoop" value="0.00">
                    </div>

                    <div class="form-group">
                        <label for="coop_loan">Coop</label>
                        <input type="text" class="form-control col-6" name="coop_loan" id="ecoop_loan" value="0.00">
                    </div>

                    <div class="form-group">
                        <label for="comm_allowance">Communication Allowance</label>
                        <input type="text" class="form-control col-6" name="comm_allowance" id="ecomm_allowance" value="0.00">
                    </div>

                    <input type="hidden" class="form-control col-6" name="salary_id" id="salary_id" value="">
                    <input type="hidden" class="form-control col-6" name="payroll_id" id="epayroll_id" value="">
                    <input type="hidden" class="form-control col-6" name="calculation" id="calculation" value="">

                    <div class="justify-content-between">
                        <button type="submit" id="salarySubmit" class="btn btn-success">Compute</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endisset

<!-- NOTIFICATIONS -->
<div class="modal fade" id="notifications">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fab fa-facebook-messenger"></i>
                    Chat Thread
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card direct-chat direct-chat-primary">
                <div class="card-body">
                    <div class="direct-chat-messages" id="showComments">
                        <!-- comments -->
                    </div>
                </div>
            </div>

            <div class="modal-body justify-content-between">
                <form action="{{ route('notifications.store') }}" method="post">

                    @csrf

                    <div class="form-group">
                        <input type="hidden" class="form-control col-6" name="salary_id" id="salary_id_notif" readonly>
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control col-12" name="comment" id="message"></textarea>
                    </div>

                    <input type="hidden" class="form-control col-6" name="payroll_id" id="payroll_id_notif" readonly>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-info">Send</button>
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