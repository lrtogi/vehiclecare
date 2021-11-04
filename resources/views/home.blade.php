@extends('company.master')

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('status') }}
        </div>
    @endif
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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">Dashboard</div>
                <div class="card-body border-bottom-primary">
                    <div class="text-center text-dark">
                        <h1>Welcome to Vehicle Care!</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-left">
        <div class="col-md-4">
            <div class="card border-left-primary shadow py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-m font-weight-bold text-primary text-uppercase mb-1">Monthly Earnings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="monthlyEarnings"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body pointer" data-target="#m_payment" data-toggle="modal" role="dialog">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-m font-weight-bold text-warning text-uppercase mb-1">Pending Approval Payment
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="paymentApproval"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-m font-weight-bold text-success text-uppercase mb-1">Total Workers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalWorkers"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fluid">
        @if (count($vehicleType) > 1)
            <div class="row">
                @foreach ($vehicleType as $vt)
                    <div class="col-md-6">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="m-0 font-weight-bold text-primary">{{ $vt->vehicle_type }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table txt-sm" role="grid"
                                    id="data-table-achievement-{{ $vt->vehicle_id }}">
                                    <thead class="thead-default">
                                        <tr role="row">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Vehicle Name</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Workers</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="thead-default">
                                        <tr role="row">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Vehicle Name</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Workers</th>
                                        </tr>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                @foreach ($vehicleType as $vt)
                    <div class="col-md-12">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="m-0 font-weight-bold text-primary">{{ $vt->vehicle_type }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table txt-sm" role="grid"
                                    id="data-table-achievement-{{ $vt->vehicle_id }}">
                                    <thead class="thead-default">
                                        <tr role="row">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Vehicle Name</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Workers</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="thead-default">
                                        <tr role="row">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Vehicle Name</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Workers</th>
                                        </tr>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="modal fade modal-size-large" id="m_payment" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">List Pending Payment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-wrapper">
                        <table class="table txt-sm" role="grid" id="data-table-achievement">
                            <thead class="thead-default">
                                <tr role="row">
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Payment Date</th>
                                    <th>Package Name</th>
                                    <th>Total Price</th>
                                    <th>Payment Price</th>
                                    <th>Action</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn" v-on:click="setToRateField()">OK</button> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-size-small" id="rejectModal" role="dialog">
        <div class="modal-dialog modal-mid">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titleReport">Reject Payment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('payment/rejectPayment') }}" method="post" class="action" id="form-reject"
                    enctype='multipart/form-data'>
                    {{ csrf_field() }}
                    <div class="modal-body" style="background-color:whitesmoke;">
                        <div class="modal-body modalsContent">

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-pure btn-sm mr-auto" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-reject" class="btn btn-sm btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal-size-small" id="approveModal" role="dialog">
        <div class="modal-dialog modal-mid">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titleReport">Approve Payment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('payment/approvePayment') }}" method="post" class="action"
                    id="form-approve" enctype='multipart/form-data'>
                    {{ csrf_field() }}
                    <div class="modal-body" style="background-color:whitesmoke;">
                        <div class="modal-body modalsContent">

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-pure btn-sm mr-auto" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-approve" class="btn btn-sm btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal-size-small" id="showDetailModal" role="dialog">
        <div class="modal-dialog modal-mid">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titleReport">Detail Payment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="background-color:whitesmoke;">
                    <div class="modal-body modalsContent">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-pure btn-sm mr-auto" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('js/fileSaver.js') }}"></script>
    <script type="text/javascript">
        var image_url = "{{ asset('images') }}";
        var paymentList = "{{ url('payment/get/search') }}";
        var dashboardUrl = "{{ url('getDashboard') }}";
        var jobUrl = "{{ url('job/get/search') }}";
        var vehicleType = {!! json_encode($vehicleType->toArray()) !!};

        function refreshPage() {
            $.ajax({
                type: 'GET',
                url: dashboardUrl,
                success: function(data) {
                    document.getElementById('monthlyEarnings').innerHTML = data.monthlyEarnings;
                    document.getElementById('paymentApproval').innerHTML = data.paymentApproval;
                    document.getElementById('totalWorkers').innerHTML = data.totalWorkers;
                }
            });
        }
        $(document).ready(function() {
            refreshPage();
        });

        function getFileName(str) {
            return str.substring(str.lastIndexOf('/') + 1)
        }
    </script>
    <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>

@endsection
