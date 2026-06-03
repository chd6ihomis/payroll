@extends('admin.master', ['activePage' => 'remittancesAll'])

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
                    <li class="breadcrumb-item active">Remittance Management</li>
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
                            <h4>Remittance Management</h4>
                        </span>
                    </ul>
                    @endauth
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
						 <thead>
                            <tr class="text-center text-success">
                                <th>Period</th>
                                <th>Tax</th>
                                <th>SSS</th>
                                <th>Pag-Ibig</th>
                                <th>PhilHealth</th>
                                <th>PhilHealth <br> OTC</th>
                                <th>Coop</th>
                                <th>Coop Loan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $period }}</td>
                                <td>{{ number_format($salaries->sum('tax'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('sss'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('pagibig'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('philhealth'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('philhealth_otc'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('coop'), 2) }}</td>
                                <td>{{ number_format($salaries->sum('coop_loan'), 2) }}</td>
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
                            <h5>Remittance Details</h5>
                        </span>

                        <span class="nav-item text-right">
                            <lead>(Total Entries: {{ $salaries->count() }})</lead>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-lg table-responsive">
                        <table class="table-bordered table-striped" style="width:100%;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Tax</th>
                                    <th rowspan="2">Pag-Ibig</th>
                                    <th rowspan="2">SSS</th>
                                    <th rowspan="2">PhilHealth</th>
                                    <th rowspan="2">PhilHealth <br> OTC</th>
                                    <th rowspan="2">Coop</th>
                                    <th rowspan="2">Coop <br> Loan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salaries as $salary)
                                <tr>
                                    <td class="text-center" style="font-size:12px">{{ $salary->employee_name }}</td>
                                    <td class="text-center">{{ number_format($salary->tax, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->pagibig, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->sss, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->philhealth_otc, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop, 2) }}</td>
                                    <td class="text-center">{{ number_format($salary->coop_loan, 2) }}</td>
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