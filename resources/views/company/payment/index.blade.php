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
                        <h6 class="m-0 font-weight-bold text-primary">{{ $pageTitle }}</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <button data-target="#m_search" data-toggle="modal" role="dialog"
                            class="btn btn-success m-0 font-weight-bold"><i class="fa fa-search" aria-hidden="true"></i>
                            Search
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table txt-sm" role="grid" id="data-table-achievement">
                        <thead class="thead-default">
                            <tr role="row">
                            <tr>
                                <th>Payment Date</th>
                                <th>Customer Name</th>
                                <th>Total Payment</th>
                                <th>Total Price</th>
                                <th>Approved</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot class="thead-default">
                            <tr role="row">
                            <tr>
                                <th>Payment Date</th>
                                <th>Customer Name</th>
                                <th>Total Payment</th>
                                <th>Total Price</th>
                                <th>Approved</th>
                                <th>Action</th>
                            </tr>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="m_search" role="dialog">
            <div class="modal-dialog modal-lg modal-mid">
                <div class="modal-content modal-mid">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h4 class="modal-title" id="titleReport">Search</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row input-wrapper">
                            <div class="col-sm-3">
                                <p class="nomarg text-left word-straight">
                                    <label class="col-form-label">Start Payment Date<span
                                            class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <span class="input-group datepicker-datelimit-init">
                                    <input type="date" data-validation="[NOTEMPTY]"
                                        data-validation-message="Date must not be empty" class="form-control"
                                        autocomplete="off" name="startdate" id="startdate" placeholder="Select Date"
                                        value="{{ date('Y-m-d') }}">
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </span>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row input-wrapper">
                            <div class="col-sm-3">
                                <p class="nomarg text-left word-straight">
                                    <label class="col-form-label">End Payment Date<span
                                            class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <span class="input-group datepicker-datelimit-init">
                                    <input type="date" data-validation="[NOTEMPTY]"
                                        data-validation-message="Date must not be empty" class="form-control"
                                        autocomplete="off" name="enddate" id="enddate" placeholder="Select Date"
                                        value="{{ date('Y-m-d') }}">
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </span>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row input-wrapper">
                            <div class="col-sm-3">
                                <p class="nomarg text-left word-straight">
                                    <label class="col-form-label">Created By Customer<span
                                            class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="created_by_customer" id="created_by_customer">
                                    <option value="all">All</option>
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row input-wrapper">
                            <div class="col-sm-3">
                                <p class="nomarg text-left word-straight">
                                    <label class="col-form-label">Approved<span class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="status" id="select-status">
                                    <option value="all">All</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                </select>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-dismiss="modal" id="btn-search-absence" onclick="searchData(this)"
                            class="btn btn-green btn-report-transaction">Search</button>
                    </div>
                    <!--      </form>      -->
                </div>
            </div>
        </div>

        <div class="modal fade modal-size-small" id="rejectModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titleReportReject"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('payment/rejectPayment') }}" method="post" id="form-delete" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalsContent">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade modal-size-small" id="approveModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titleReportApprove"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('payment/approvePayment') }}" method="post" id="form-delete" target="_self">
                        {{ csrf_field() }}
                        <div class="modal-body modalsContent">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-pure mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-save"
                                class="btn btn-primary btn-report-transaction">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @section('script')

        <script type="text/javascript">
            var searchUrl = "{{ url('payment/get/search') }}";
            var detailUrl = "{{ url('payment/detail') }}";
            var app = new Vue({
                el: '#m_search',
                delimiters: ['${', '}'],
                data: {
                    startdate: "{{ date('d-m-Y') }}",
                    enddate: "{{ date('d-m-Y') }}",
                    vehicleType: "all",
                    status: 'all',
                }
            });
        </script>
        <script type="text/javascript" src="{{ asset('js/payment/payment-table.js') }}"></script>
    @endsection
