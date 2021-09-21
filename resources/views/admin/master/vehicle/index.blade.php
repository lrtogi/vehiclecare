@extends('admin.master')

@section('title', $pageTitle)

@section('content')

    <div class="container-fluid">
        <div class="card mb-4 py-3 border-left-primary">
            <div class="card-body text-dark">
                <h3>{{ $pageTitle }}</h3>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="m-0 font-weight-bold text-primary">Vehicle Datas</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a class="btn btn-primary m-0 font-weight-bold text-white"
                            href="{{ route('admin/vehicleType/showForm') }}">Add Vehicle</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Vehicle ID</th>
                                <th>Vehicle Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Vehicle ID</th>
                                <th>Vehicle Type</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($vehicle as $v)
                                <tr>
                                    <td>{{ $v->vehicle_id }}</td>
                                    <td>{{ $v->vehicle_type }}</td>
                                    <td>
                                        <a href="{{ route('admin/vehicleType/showForm', $v->vehicle_id) }}"
                                            class="btn btn-warning btn-sm btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-edit"></i>
                                            </span><span class="text">Edit</span></a>
                                        <button role="dialog" class="btn btn-danger btn-sm btn-icon-split"
                                            title="Delete Vehicle Type" data-vehicle_id="{{ $v->vehicle_id }}"
                                            data-vehicle_type="{{ $v->vehicle_type }}" data-target="#deleteModal"
                                            onclick="locked(this)">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span><span class="text">Hapus</span></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade modal-size-small" id="deleteModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titleReport"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('admin/vehicleType/delete') }}" method="post" id="form-delete" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalDelete">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save"
                                class="btn btn-danger btn-report-transaction">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @section('script')

        <!-- Page level plugins -->
        <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Page level custom scripts -->
        <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                window.locked = function(element) {
                    var data = $(element).data('vehicle_id');
                    var dataName = $(element).data('vehicle_type');
                    $('#titleReport').html("Delete Vehicle Type");
                    var html = "<input type='hidden' name='idAccess' value='locked' />";
                    html += "<input type='hidden' name= 'vehicle_id' value='" + data + "' />";
                    html += "Hapus Vehicle Type <strong>'" + dataName + "'</strong> ?";
                    $('.modalDelete').html(html);
                    $('#deleteModal').modal('show');
                };
            });
        </script>
    @endsection
