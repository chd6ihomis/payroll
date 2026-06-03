@extends('admin.master', ['activePage' => 'payrollsAll'])

@section('content')
<div class="modal fade" id="selectPeriod">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-cog"></i>
                    Payroll Period
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body justify-content-between">
                <form action="{{ route('showall-payrolls') }}" method="post">

                    @csrf

                    <div class="form-group">
                        <label for="period">Period</label>
                        <select class="form-control form-control-sm select2" name="period" id="period">
                            <option selected disabled>{{ 'Select Period' }}</option>
                            @forelse($payrolls as $payroll)
                            <option>{{ $payroll->period }}</option>
                            @empty
                            <option>"No Period Found"</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control form-control-sm select2" name="type" id="type">
                            <option selected value="standard"> Standard </option>
                            <option value="dropped">Dropped</option>
                            <option value="hrh">Human Resource for Health</option>
                        </select>
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