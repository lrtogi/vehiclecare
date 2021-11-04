@extends('company.master')

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
                        <h6 class="m-0 font-weight-bold text-primary">Payment Method</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a class="btn btn-primary m-0 font-weight-bold text-white"
                            href="{{ route('paymentMethod/showForm') }}">Add Payment Method</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Value</th>
                                <th>On Behalf Of</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Payment Method</th>
                                <th>Value</th>
                                <th>On Behalf Of</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($paymentMethod as $p)
                                <tr class="{{ $p->void == 1 ? 'bg-warning' : '' }}">
                                    <td>{{ $p->method }}</td>
                                    <td>{{ $p->value }}</td>
                                    <td>{{ $p->on_behalf_of }}</td>
                                    <td>{{ $p->void == 0 ? 'Active' : 'Not Active' }}</td>
                                    <td>
                                        <a href="{{ route('paymentMethod/showForm', $p->payment_method_id) }}"
                                            class="btn btn-warning btn-sm btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-edit"></i>
                                            </span><span class="text">Edit</span></a>
                                        @if ($p->void == 0)
                                            <button role="dialog" class="btn btn-danger btn-sm btn-icon-split"
                                                title="Void Payment Method"
                                                data-payment_method_id="{{ $p->payment_method_id }}"
                                                data-method="{{ $p->method }}" data-target="#voidModal"
                                                onclick="locked(this)">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-exclamation"></i>
                                                </span><span class="text">Void</span></button>
                                        @else
                                            <button role="dialog" class="btn btn-success btn-sm btn-icon-split"
                                                title="Unvoid Payment Method"
                                                data-payment_method_id="{{ $p->payment_method_id }}"
                                                data-method="{{ $p->method }}" data-target="#unvoidModal"
                                                onclick="unlocked(this)">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-check"></i>
                                                </span><span class="text">Unvoid</span></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade modal-size-small" id="voidModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="voidTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('paymentMethod/void') }}" method="post" id="form-void" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalVoid">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save" class="btn btn-primary btn-report-transaction">Void</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade modal-size-small" id="unvoidModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="unvoidTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('paymentMethod/unvoid') }}" method="post" id="form-unvoid" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalUnvoid">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save"
                                class="btn btn-primary btn-report-transaction">Unvoid</button>
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

        <script>
            $(document).ready(function() {
                window.locked = function(element) {
                    var data = $(element).data('payment_method_id');
                    var dataName = $(element).data('method');
                    $('#voidTitle').html("Void Payment Method");
                    var html = "<input type='hidden' name='idAccess' value='locked' />";
                    html += "<input type='hidden' name= 'payment_method_id' value='" + data + "' />";
                    html += "Void Payment Method <strong>'" + dataName + "'</strong> ?";
                    $('.modalVoid').html(html);
                    $('#voidModal').modal('show');
                };
                window.unlocked = function(element) {
                    var data = $(element).data('payment_method_id');
                    var dataName = $(element).data('method');
                    $('#rejectTitle').html("Unvoid Payment Method");
                    var html = "<input type='hidden' name='idAccess' value='locked' />";
                    html += "<input type='hidden' name= 'payment_method_id' value='" + data + "' />";
                    html += "Unvoid Payment Method <strong>'" + dataName + "'</strong> ?";
                    $('.modalUnvoid').html(html);
                    $('#unvoidModal').modal('show');
                };
            });
        </script>

    @endsection
