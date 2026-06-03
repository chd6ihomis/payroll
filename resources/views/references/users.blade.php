@extends('admin.master', ['activePage' => 'references'])

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
                    <li class="breadcrumb-item active">Users Management</li>
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
                            <h5>Users Management</h5>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table id="employeetbl" class="ui celled table table-bordered table-striped" style="width:100%; font-size:14px;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Office</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">{{ \DB::table('office')->where('id', $user->office)->first()->office_name }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('refs.reset', $user->id) }}" method="get">
                                            @csrf
                                            @method('put')
                                            <button type="button" class="btn btn-info btn-sm" rel="tooltip" title="Reset" onclick="confirm('{{ __("Reset User Password?") }}') ? this.parentElement.submit() : ''">Reset Password</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Payroll records found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payrollAdd">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    New Payroll
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('payrolls.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="office">Office</label>
                        <input type="text" readonly class="form-control-plaintext" name="office" id="office" placeholder="" value="{{ \DB::table('office')->where('id', auth()->user()->office)->first()->office_name }}" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label for="PeriodDate">Period Start</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" name="payroll_period" class="form-control float-right date-picker" id="payroll_period">
                        </div>
                        <!-- /.input group -->
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control form-control-sm select2" name="type" id="type">
                            <option selected value="standard"> Standard </option>
                            <option value="dropped">Dropped</option>
                        </select>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade form-modal" id="payrollDuplicate" onsubmit="loadSpinner()">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info">
                    <i class="fas fa-clone"></i>
                    Duplicate Payroll
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('duplicatepayroll') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="office">Office</label>
                        <input type="text" readonly class="form-control-plaintext" name="office" id="office" placeholder="" value="{{ \DB::table('office')->where('id', auth()->user()->office)->first()->office_name }}" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                        <label for="PeriodDate">Period Start</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" name="payroll_period" class="form-control float-right date-picker" id="payroll_period">
                        </div>
                        <!-- /.input group -->
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control form-control-sm select2" name="type" id="type">
                            <option selected value="standard"> Standard </option>
                            <option value="dropped">Dropped</option>
                        </select>
                    </div>

                    <input type="hidden" name="getpayroll" class="form-control float-right" id="getpayroll">

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection