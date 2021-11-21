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
                        <h6 class="m-0 font-weight-bold text-primary">Job List</h6>
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
                                                    <th>Index</th>
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
                                                    <th>Index</th>
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
                                                    <th>Index</th>
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
                                                    <th>Index</th>
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
                                    <label class="col-form-label">Select Date<span class="text-danger"></span></label>
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-dismiss="modal" id="btn-search-absence" onclick="searchData(this)"
                            class="btn btn-green btn-report-transaction">Search</button>
                    </div>
                    <!--      </form>      -->
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
            var dateTime = "{{ date('Y-m-d') }}";
            var vehicleType = {!! json_encode($vehicleType->toArray()) !!};
            var jobUrl = "{{ url('job/get/search') }}";
        </script>

        <script type="text/javascript" src="{{ asset('js/job/job-table.js') }}"></script>

    @endsection
