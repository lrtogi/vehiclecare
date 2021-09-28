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
                        <a class="btn btn-primary m-0 font-weight-bold text-white"
                            href="{{ route('transaction/showForm') }}">Add Transaction</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table txt-sm" role="grid" id="data-table-achievement">
                        <thead class="thead-default">
                            <tr role="row">
                            <tr>
                                <th>Transaction Date</th>
                                <th>Customer Name</th>
                                <th>Order Date</th>
                                <th>Package</th>
                                <th>Vehicle Type</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </tr>
                        </thead>
                        <tfoot class="thead-default">
                            <tr role="row">
                            <tr>
                                <th>Transaction Date</th>
                                <th>Customer Name</th>
                                <th>Order Date</th>
                                <th>Package</th>
                                <th>Vehicle Type</th>
                                <th>Total Price</th>
                                <th>Status</th>
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
                                    <label class="col-form-label">Start Order Date<span
                                            class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <span class="input-group datepicker-datelimit-init">
                                    <input type="date" data-validation="[NOTEMPTY]"
                                        data-validation-message="Date must not be empty" class="form-control"
                                        autocomplete="off" name="startdate" id="startdate" placeholder="Select Date"
                                        value="{{ date('Y-m-01') }}">
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
                                    <label class="col-form-label">End Order Date<span class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <span class="input-group datepicker-datelimit-init">
                                    <input type="date" data-validation="[NOTEMPTY]"
                                        data-validation-message="Date must not be empty" class="form-control"
                                        autocomplete="off" name="enddate" id="enddate" placeholder="Select Date"
                                        value="{{ date('Y-m-t') }}">
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
                                    <label class="col-form-label">Vehicle Type<span class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <select v-model="vehicleType" class="form-control select2" name="vehicleType"
                                    id="select-vehicleType">
                                    <option value="all">All Vehicle Type</option>
                                    @foreach ($vehicleType as $v)
                                        <option value="{{ $v->vehicle_id }}">{{ $v->vehicle_type }}</option>
                                    @endforeach
                                </select>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row input-wrapper">
                            <div class="col-sm-3">
                                <p class="nomarg text-left word-straight">
                                    <label class="col-form-label">Status<span class="text-danger"></span></label>
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="status" id="select-status">
                                    <option value="all">All</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Paid</option>
                                    <option value="2">Canceled</option>
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

        <div class="modal fade modal-size-small" id="deleteModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titleReport"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('transaction/delete') }}" method="post" id="form-delete" target="_self">
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

        <script type="text/javascript">
            var searchUrl = "{{ url('transaction/get/search') }}";
            var formUrl = " {{ url('transaction/showForm') }}";
            var detailUrl = "{{ url('transaction/detail') }}";
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

        <script type="text/javascript" src="{{ asset('js/transaction/transaction-table.js') }}"></script>
    @endsection
