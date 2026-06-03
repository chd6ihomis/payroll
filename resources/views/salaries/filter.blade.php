@extends('admin.master', ['activePage' => 'salariesAll'])

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
                    <li class="breadcrumb-item"><a href="{{ route('filter-salary') }}">Salaries Management</a></li>
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
                            <h4>Salary Management</h4>
                        </span>
                    </ul>
                    @endauth
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
						 <thead>
                            <tr class="text-center text-success">
                                <th>Period</th>
                                <th>SOA</th>
                                <th>SOA CONAP</th>
                                <th>Deductions</th>
                                <th>Tax</th>
                                <th>SSS</th>
                                <th>Pag-Ibig</th>
                                <th>PhilHealth</th>
                                <th>Coop</th>
                                <th>Coop Loan</th>
                                <th>Net Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $period }}</td>
                                <td>{{ number_format($salaries->sum('soa'), 2) }}</td>
                                <td>{{ number_format($salaries->where('isConap', 'Y')->sum('soa'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('deductions'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('tax'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('sss'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('pagibig'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('philhealth'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('coop'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('coop_loan'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('net_amt'), 2) }}</td>
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
                            <lead class="d-inline">Sub-Total : {{ number_format($salaries->sum('net_amt'), 2)}}</lead>
                            <lead>(Total Entries: {{ $salaries->count() }})</lead>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        
                        <table id="employeetbl" class="ui celled table table-bordered table-striped" style="width:100%; font-size:11px">
                            <thead class="text-center text-success">
                                <tr>
                                    <th rowspan="2"></th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Monthly <br> Rate</th>
                                    <th rowspan="2">{{ substr($period,0,3) }} <br> {{ substr($period, 4, 11)}}</th>
                                    <th rowspan="1" colspan="3">Absences/Late <br> Undertime</th>
                                    <th rowspan="2">Total</th>
                                    <th rowspan="2">SOA</th>
                                    <th rowspan="2">Comm <br> Allowance</th>
                                    <th rowspan="2">Tax</th>
                                    <th rowspan="2">Pag-Ibig</th>
                                    <th rowspan="2">SSS</th>
                                    <th rowspan="2">PhilHealth</th>
                                    <th rowspan="2">PhilHealth OTC</th>
                                    <th rowspan="2">Coop</th>
                                    <th rowspan="2">Coop <br> Loan</th>
                                    <th rowspan="2">Net <br> Amount</th>
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
                                    <td class="text-center bg-danger" style="font-size:12px">{{ $salary->calculation }} </td>
                                    @break
                                    @default
                                    <td class="text-center bg-secondary" style="font-size:12px">{{ $salary->calculation }}</td>
                                    @endswitch
                                    <td class="text-center" style="font-size:12px">{{ $salary->employee_name }}</td>
                                    <td class="text-center">{{ number_format($salary->smonthly_rate, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->basic, 2) }}</td>
                                    <td class="text-center">{{ $salary->day }}</td>
                                    <td class="text-center">{{ $salary->hr }}</td>
                                    <td class="text-center">{{ $salary->min }}</td>
                                    <td class="text-center">{{ number_format($salary->deductions, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->soa, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->comm_allowance, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->tax, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->pagibig, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->sss, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth_otc, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop_loan, 2) }}</td>
                                    <th class="text-center">{{ number_format($salary->net_amt, 2) }}</th>
                                    <td class="text-center">{{ $salary->remarks }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('notifications.update', $salary->sid) }}" method="post">
                                            @csrf
                                            @method('put')
                                            @if($salary->isCorrect == 'D')
                                            <button type="button" class="btn btn-success btn-sm" title="View" onclick="confirm('{{ __("Mark as correct and close thread?") }}') ? this.parentElement.submit() : ''"><i class="fas fa-check"></i></button>
                                            <button type="button" id="salaryIncorrect" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#incorrectSalary" data-id="{{ $salary->sid }}"><i class="fas fa-times"></i></button>
                                            @elseif($salary->isCorrect == 'N')
                                            <button type="button" class="btn btn-success btn-sm" title="View" onclick="confirm('{{ __("Mark as correct and close thread?") }}') ? this.parentElement.submit() : ''"><i class="fas fa-check"></i></button>
                                            @else
                                            <button type="button" id="salaryIncorrect" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#incorrectSalary" data-id="{{ $salary->sid }}"><i class="fas fa-times"></i></button>
                                            @endif
											<button type="button" id="showNotifications" class="btn btn-info btn-sm pr-0" data-toggle="modal" data-target="#notifications" data-id="{{ $salary->sid }}"><i class="fab fa-facebook-messenger"></i><span class="badge badge-danger navbar-badge">{{ $notifications->where('salary_id', $salary->sid)->count() }}</span></button>
                                        </form>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="17" class="text-center">No Salary records found!</td>
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incorrect Salary -->
<div class="modal fade" id="incorrectSalary">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fab fa-facebook-messenger"></i>
                    Salary Remarks
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body justify-content-between">
                <form action="{{ route('notifications.store') }}" method="post">

                    @csrf

                    <div class="form-group">
                        <label for="salary_id">Salary ID</label>
                        <input type="text" class="form-control col-6" name="salary_id" id="salary_id_inc" readonly>
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control col-12" name="comment" id="comment"></textarea>
                    </div>

                    <input type="hidden" class="form-control col-6" name="payroll_id" id="payroll_id_inc" readonly>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Send</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

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