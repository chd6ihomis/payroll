@extends('admin.master', ['activePage' => 'refs.fundsources'])

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
                    <li class="breadcrumb-item active">Fund Sources Management</li>
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
                            <h5>Fund Sources Management</h5>
                        </span>
                        <span class="nav-item text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fundsourceAdd"> Add Fund Source </button>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table id="employeetbl" class="ui celled table table-bordered table-striped" style="width:100%; font-size:14px;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">Description</th>
                                    <th scope="col">MFO/PAP</th>
                                    <th scope="col">Conap</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fundsources as $fundsource)
                                <tr>
                                    <td class="text-center">{{ $fundsource->desc }}</td>
                                    <td class="text-center">{{ $fundsource->mfo_pap }}</td>
                                    <td class="text-center">{{ $fundsource->isConap }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('refs.fundsourcesDelete', $fundsource->id) }}" method="get">
                                            @csrf
                                            @method('delete')
                                            <button type="button" id="editFundsource" class="btn btn-success btn-sm" rel="tooltip" title="Edit" data-toggle="modal" data-target="#fundsourceEdit" data-id="{{ $fundsource->id }}"><i class="fas fa-edit"></i> Edit</button>
                                            <button type="button" class="btn btn-danger btn-sm" rel="tooltip" title="Delete" onclick="confirm('{{ __("Confirm Delete Fund Source?") }}') ? this.parentElement.submit() : ''"><i class="fas fa-trash-alt" style="--fa-primary-color: white"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Fund Source records found!</td>
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

<div class="modal fade" id="fundsourceAdd">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    New FundSource
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			
            <div class="modal-body">
			<small class="text-danger">Fund Sources for SAA & CONAP should start with the words "SAA" / "CONAP" to avoid errors.</small>
                <form action="{{ route('refs.fundsourcesAdd') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="desc">Description</label>
                        <input type="text" class="form-control col-10" name="desc" id="desc" required>
                    </div>
                    <div class="form-group">
                        <label for="mfo_pap">MFO/PAP</label>
                        <input type="text" class="form-control col-6" name="mfo_pap" id="mfo_pap" oninput="this.value = this.value.toUpperCase()" required>
                    </div>

                    <div class="form-group">
                        <label for="isConap">isConap</label>
                        <select class="form-control col-6 form-control-sm select2" name="isConap" id="isConap">
                            <option value="N">NO</option>
                            <option value="Y">YES</option>
                        </select>
                    </div>

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

@isset($fundsource)
<div class="modal fade" id="fundsourceEdit">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    Edit Fundsource
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('refs.fundsourcesUpdate', $fundsource->id) }}" method="get">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="desc">Description</label>
                        <input type="text" class="form-control col-6" name="desc" id="desc_e" required>
                    </div>
                    <div class="form-group">
                        <label for="mfo_pap">MFO/PAP</label>
                        <input type="text" class="form-control col-6" name="mfo_pap" id="mfo_pap_e" oninput="this.value = this.value.toUpperCase()" required>
                    </div>

                    <div class="form-group">
                        <label for="isConap">isConap</label>
                        <select class="form-control col-6 form-control-sm select2" name="isConap" id="isConap_e">
                        </select>
                    </div>

                    <input type="hidden" class="form-control col-6" name="id" id="id_e">

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