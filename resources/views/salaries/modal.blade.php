@extends('admin.master', ['activePage' => 'salariesAll'])

@section('content')
<div class="modal fade" id="selectPeriod">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-cog"></i>
                    Salaries Period
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body justify-content-between">
                <form action="{{ route('showall-salaries') }}" method="get">

                    @csrf

                    <div class="form-group">
                        <label for="period">Period</label>
                        <select class="form-control form-control-sm select2" name="period" id="period">
                            <option selected disabled>{{ 'Select Period' }}</option>
                            @forelse($salaries as $salary)
                            <option>{{ $salary->period }}</option>
                            @empty
                            <option>"No Period Found"</option>
                            @endforelse
                        </select>
                    </div>
					
					
                    <div class="form-group">
                        <label for="division">Division</label>
                        <select class="form-control form-control-sm select2" name="division" id="division">
                            <option selected disabled>{{ 'Select Division' }}</option>
                            @forelse($divisions as $division)
                            <option>{{ $division->division }}</option>
                            @empty
                            <option>"No Division has processed salary"</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type">Payroll Type</label>
                        <select class="form-control form-control-sm select2" name="type" id="type">
                            <option selected value="standard">{{ 'Standard' }}</option>
                            <option value="dropped">{{ 'Dropped' }}</option>
                            <option value="hrh">{{ 'Human Resource for Health' }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control form-control-sm select2" name="status" id="status">
                            <option selected value="1">{{ 'Select All' }}</option>
                            <option value="2">{{ 'Incorrect Only' }}</option>
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