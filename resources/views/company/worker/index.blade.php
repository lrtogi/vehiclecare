@extends(auth()->user()->user_type == 3 ? 'admin.master' : 'company.master')

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
                        <h6 class="m-0 font-weight-bold text-primary">Worker data</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a class="btn btn-primary m-0 font-weight-bold text-white"
                            href="{{ route('worker/showForm') }}">Add Worker</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Worker ID</th>
                                <th>Worker Name</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Active</th>
                                <th>Approved</th>
                                <th>Has Web App Access</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Worker ID</th>
                                <th>Worker Name</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Active</th>
                                <th>Approved</th>
                                <th>Has Web App Access</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($worker as $w)
                                <tr class="{{ $w->approved == 0 ? 'bg-warning' : '' }}">
                                    <td>{{ $w->worker_id }}</td>
                                    <td>{{ $w->worker_name }}</td>
                                    <td>{{ $w->no_telp }}</td>
                                    <td>{{ $w->alamat }}</td>
                                    <td>{{ $w->active == 0 ? 'Not Active' : 'Active' }}</td>
                                    <td>
                                        {{ $w->approved == 0 ? 'Not Approved' : 'Approved' }}</td>
                                    <td>{{ $w->hasAccess }}</td>
                                    <td>
                                        @if ($w->approved == 1)
                                            <a href="{{ route('worker/showForm', $w->worker_id) }}"
                                                class="btn btn-warning btn-sm btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-edit"></i>
                                                </span><span class="text">Edit</span></a>
                                            <button role="dialog" class="btn btn-danger btn-sm btn-icon-split"
                                                title="Delete Worker" data-worker_id="{{ $w->worker_id }}"
                                                data-worker_name="{{ $w->worker_name }}" data-target="#deleteModal"
                                                onclick="locked(this)">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span><span class="text">Hapus</span></button>
                                        @else
                                            <button role="dialog" class="btn btn-success btn-sm btn-icon-split"
                                                title="Approve Worker" data-worker_id="{{ $w->worker_id }}"
                                                data-worker_name="{{ $w->worker_name }}" data-target="#approveModal"
                                                onclick="approve(this)">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </span><span class="text">Approve</span></button>
                                            <button role="dialog" class="btn btn-danger btn-sm btn-icon-split"
                                                title="Reject Worker" data-worker_id="{{ $w->worker_id }}"
                                                data-worker_name="{{ $w->worker_name }}" data-target="#rejectModal"
                                                onclick="reject(this)">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-times"></i>
                                                </span><span class="text">Reject</span></button>
                                        @endif
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
                    <form action="{{ route('worker/delete') }}" method="post" id="form-delete" target="_self">
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
        <div class="modal fade modal-size-small" id="approveModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="approveTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('worker/approve') }}" method="post" id="form-approve" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalApprove">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save"
                                class="btn btn-success btn-report-transaction">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade modal-size-small" id="rejectModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="rejectTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('worker/reject') }}" method="post" id="form-reject" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalReject">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save"
                                class="btn btn-danger btn-report-transaction">Reject</button>
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
                    var data = $(element).data('worker_id');
                    var dataName = $(element).data('worker_name');
                    $('#titleReport').html("Void Worker");
                    var html = "<input type='hidden' name='idAccess' value='locked' />";
                    html += "<input type='hidden' name= 'worker_id' value='" + data + "' />";
                    html += "Void Worker <strong>'" + dataName + "'</strong> ?";
                    html += "<br/><a class='text-danger'><strong>This action cannot be undone</strong></a>"
                    $('.modalDelete').html(html);
                    $('#deleteModal').modal('show');
                };
                window.approve = function(element) {
                    var data = $(element).data('worker_id');
                    var dataName = $(element).data('worker_name');
                    $('#approveTitle').html("Approve Worker");
                    var html = "<input type='hidden' name='idAccess' value='locked' />";
                    html += "<input type='hidden' name= 'worker_id' value='" + data + "' />";
                    html += "Approve Worker <strong>'" + dataName + "'</strong> ?";
                    html +=
                        "<div class='form-group row'><label for='has_access' class='col-md-6 col-form-label'>Has Web App Access : </label>" +
                        "<div class='col-md-6'><select name='has_access' class='form-control'><option value='1'>No Web Access</option><option value='2'>Has Web Access</option></select>" +
                        "</div></div>"
                    $('.modalApprove').html(html);
                    $('#approveModal').modal('show');
                };
                window.reject = function(element) {
                    var data = $(element).data('worker_id');
                    var dataName = $(element).data('worker_name');
                    $('#rejectTitle').html("Reject Worker");
                    var html = "<input type='hidden' name='idAccess' value='locked' />";
                    html += "<input type='hidden' name= 'worker_id' value='" + data + "' />";
                    html += "Reject Worker <strong>'" + dataName + "'</strong> ?";
                    $('.modalReject').html(html);
                    $('#rejectModal').modal('show');
                };
            });
        </script>
    @endsection
