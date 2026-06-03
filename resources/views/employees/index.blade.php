@extends('admin.master', ['activePage' => 'employees'])

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
                    <li class="breadcrumb-item active">Employee Management</li>
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
                            <h5>Employees Management <span class="text-success">(Active - {{ $total_employees->where('status', 'true')->count() }})</span> <span class="text-danger">(Inactive - {{ $total_employees->where('status', 'false')->count() }})</span></h5>
                        </span>
						@if(auth()->user()->role == '0')
                        <span class="nav-item text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#employeeAdd"> Add Employee </button>
                        </span>
                        @endif
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table id="employeetbl" class="ui celled table table-bordered table-striped" style="width:100%; font-size:14px;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Office</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
									<th scope="col">Landbank</th>
                                    <th scope="col">Pag-Ibig</th>
                                    <th scope="col">Fund Source</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                <tr>
                                    <td class="text-center {{ $employee->status == 'true' ? 'bg-success' : 'bg-danger' }}"></td>
                                    <td class="text-center">{{ $employee->employee_id }}</td>
                                    <td class="text-left">{{ $employee->employee_name }}</td>
                                    <td class="text-center">{{ $employee->position }}</td>
                                    <td class="text-center">{{ \DB::table('office')->where('id', $employee->office)->first()->shortname }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($employee->start_date)->toFormattedDateString() }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($employee->end_date)->toFormattedDateString() }}</td>
									<td class="text-center">{{ $employee->lbp_num == 0 ? '-' :  $employee->lbp_num }}</td>
                                    <td class="text-center">{{ $employee->pagibig_num == 0 ? '-' :  $employee->pagibig_num }}</td>
                                    <td class="text-center">{{ \DB::table('fund_source')->where('id', $employee->fs)->first()->desc }}</td>
                                    <td class="text-center">
                                        @if(auth()->user()->role == '0')
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="button" id="editEmployee" class="btn btn-success btn-sm" data-toggle="modal" data-target="#employeeEdit" data-id="{{ $employee->id }}"><i class="fas fa-edit"></i></button>
                                            <button type="button" disabled class="btn btn-danger btn-sm" rel="tooltip" title="Delete" onclick="confirm('{{ __("Delete Employee Data?") }}') ? this.parentElement.submit() : ''"><i class="fas fa-trash-alt" style="--fa-primary-color: white"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Employee records found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="nav justify-content-start">
                        <span class="nav-item bg-success text-left p-1">
                            <em>Active</em>
                        </span>
                        <span class="nav-item bg-danger text-left p-1">
                            <em>Inactive</em>
                        </span>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="employeeAdd">
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
                <form action="{{ route('employees.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="employee_id">Employee ID</label>
                        <input type="text" class="form-control col-6" name="employee_id" id="employee_id" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_name">Employee Name</label>
                        <input type="text" class="form-control col-6" name="employee_name" id="employee_name" oninput="this.value = this.value.toUpperCase()" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birth Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datemask col-6" id="birthdate" name="birthdate" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_num">Contact Number</label>
                        <input type="text" class="form-control col-6" name="contact_num" id="contact_num">
                    </div>

                    <div class="form-group">
                        <label for="position">Position <small><em>(Pls. input the shorten version)</em></small></label>
                        <input type="text" class="form-control col-6" name="position" id="position">
                    </div>

                    <div class="form-group">
                        <label for="office">Office</label>
                        <input type="text" class="form-control col-12" name="office" id="office" value="{{ \DB::table('office')->where('id', auth()->user()->office)->first()->office_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="monthly_rate">Monthly Rate</label>
                        <input type="text" class="form-control col-6" name="monthly_rate" id="monthly_rate" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="fund_source">Fund Source <small><em>(Pls. input the source & year; ex. GOP 2022)</em></small></label>
                        <select class="form-control form-control-sm select2" name="fund_source" id="fund_source">
                            <option selected disabled>{{ 'Select Fund Source' }}</option>
                            @forelse($fund_sources = \DB::table('fund_source')->get() as $fund_source)
                            <option value="{{$fund_source->id}}">{{ $fund_source->desc .' ('. $fund_source->mfo_pap .')' }}</option>
                            @empty
                            <option>"No fund source Found"</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lbp_num">LBP Number <small><em>(xxxx-xxxx-xx)</em></small></label>
                        <input type="text" class="form-control col-6" name="lbp_num" id="lbp_num" required>
                    </div>

                    <div class="form-group">
                        <label for="tin_num">TIN Number <small><em>(xxx-xxx-xxx)</em></small></label>
                        <input type="text" class="form-control col-6" name="tin_num" id="tin_num" required>
                    </div>

                    <div class="form-group">
                        <label for="pagibig_num">Pag-Ibig Number <small><em>(xxxx-xxxx-xxxx)</em></small></label>
                        <input type="text" class="form-control col-6" name="pagibig_num" id="pagibig_num" value="0">
                    </div>

                    <div class="form-group">
                        <label for="sss_num">SSS Number <small><em>(xx-xxxxxxx-x)</em></small></label>
                        <input type="text" class="form-control col-6" name="sss_num" id="sss_num" value="0">
                    </div>

                    <div class="form-group">
                        <label for="philhealth_num">PhilHealth Number <small><em>(xx-xxxxxxxxx-x)</em></small></label>
                        <input type="text" class="form-control col-6" name="philhealth_num" id="philhealth_num" value="0">
                    </div>

                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datemask col-6" id="start_date" name="start_date" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datemask col-6" id="end_date" name="end_date" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                        </div>
                    </div>

                    <input type="hidden" class="form-control col-6" name="status" id="status" value="true">

                    <div class="justify-content-between">
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

@isset($employee)
<div class="modal fade" id="employeeEdit">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    Edit Employee
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employees.update', $employee->id) }}" method="post">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="status">Status</label>
                        <input class="form-control col-6" type="checkbox" name="status" id="status_e" data-bootstrap-switch>
                    </div>

                    <div class="form-group">
                        <label for="employee_id">Employee ID</label>
                        <input type="text" class="form-control col-6" name="employee_id" id="employee_id_e" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_name">Employee Name</label>
                        <input type="text" class="form-control col-6" name="employee_name" id="employee_name_e" oninput="this.value = this.value.toUpperCase()" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birth Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datemask col-6" id="birthdate_e" name="birthdate" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_num">Contact Number</label>
                        <input type="text" class="form-control col-6" name="contact_num" id="contact_num_e">
                    </div>

                    <div class="form-group">
                        <label for="position">Position <small><em>(Pls. input the shorten version)</em></small></label>
                        <input type="text" class="form-control col-6" name="position" id="position_e">
                    </div>

                    <div class="form-group">
                        <label for="office">Office</label>
                        <input type="text" class="form-control col-12" name="office" id="office_e" value="{{ \DB::table('office')->where('id', auth()->user()->office)->first()->office_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="monthly_rate">Monthly Rate</label>
                        <input type="text" class="form-control col-6" name="monthly_rate" id="monthly_rate_e" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="fund_source">Fund Source <small><em>(Pls. input the source & year; ex. GOP 2022)</em></small></label>
                        <select class="form-control form-control-sm select2" name="fund_source" id="fund_source_e">
                            @forelse($fund_sources = \DB::table('fund_source')->where('isActive', 'Y')->get() as $fund_source)
                            <option value="{{$fund_source->id}}">{{ $fund_source->desc .' ('. $fund_source->mfo_pap .')' }}</option>
                            @empty
                            <option>"No fund source Found"</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lbp_num">LBP Number <small><em>(xxxx-xxxx-xx)</em></small></label>
                        <input type="text" class="form-control col-6" name="lbp_num" id="lbp_num_e" required>
                    </div>

                    <div class="form-group">
                        <label for="tin_num">TIN Number <small><em>(xxx-xxx-xxx)</em></small></label>
                        <input type="text" class="form-control col-6" name="tin_num" id="tin_num_e" required>
                    </div>

                    <div class="form-group">
                        <label for="pagibig_num">Pag-Ibig Number <small><em>(xxxx-xxxx-xxxx)</em></small></label>
                        <input type="text" class="form-control col-6" name="pagibig_num" id="pagibig_num_e" value="0">
                    </div>

                    <div class="form-group">
                        <label for="sss_num">SSS Number <small><em>(xx-xxxxxxx-x)</em></small></label>
                        <input type="text" class="form-control col-6" name="sss_num" id="sss_num_e" value="0">
                    </div>

                    <div class="form-group">
                        <label for="philhealth_num">PhilHealth Number <small><em>(xx-xxxxxxxxx-x)</em></small></label>
                        <input type="text" class="form-control col-6" name="philhealth_num" id="philhealth_num_e" value="0">
                    </div>

                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datemask col-6" id="start_date_e" name="start_date" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control datemask col-6" id="end_date_e" name="end_date" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                        </div>
                    </div>

                    <input type="hidden" class="form-control col-6" name="id" id="id_e" value="">

                    <div class="justify-content-between">
                        <button type="submit" class="btn btn-success">Update</button>
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

@endsection