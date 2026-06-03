@extends('admin.master', ['activePage' => 'refs.signatories'])

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
                    <li class="breadcrumb-item active">Signatories Management</li>
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
                            <h5>Signatories Management</h5>
                        </span>
                    </ul>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="table table-sm table-responsive">
                        <table id="employeetbl" class="ui celled table table-bordered table-striped" style="width:100%; font-size:14px;">
                            <thead class="text-center text-success">
                                <tr>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($signatories as $signatory)
                                <tr>
                                    <td class="text-center">{{ $signatory->name }}</td>
                                    <td class="text-center">{{ $signatory->position }}</td>
                                    <td class="text-center">{{ $signatory->division }}</td>
                                    <td class="text-center">
                                        <button type="button" id="editSignatory" class="btn btn-success btn-sm" rel="tooltip" title="Edit" data-toggle="modal" data-target="#signatoryEdit" data-id="{{ $signatory->id }}"><i class="fas fa-edit"></i> Edit</button>
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

@isset($signatory)
<div class="modal fade" id="signatoryEdit">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fas fa-plus-square"></i>
                    Edit Signatory
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('refs.signatoriesUpdate', $signatory->id) }}" method="get">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control col-12" name="name" id="name_e" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control col-6" name="position" id="position_e">
                    </div>

                    <div class="form-group">
                        <label for="division">Division</label>
                        <input type="text" class="form-control col-6" name="division" id="division_e" oninput="this.value = this.value.toUpperCase()">
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