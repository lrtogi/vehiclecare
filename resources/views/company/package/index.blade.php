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
                        <h6 class="m-0 font-weight-bold text-primary">Packages</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a class="btn btn-primary m-0 font-weight-bold text-white"
                            href="{{ route('package/showForm') }}">Add Package</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>Vehicle Type</th>
                                <th>Price</th>
                                <th>Discount (%)</th>
                                <th>Discounted Price</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Package Name</th>
                                <th>Vehicle Type</th>
                                <th>Price</th>
                                <th>Discount (%)</th>
                                <th>Discounted Price</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($package as $p)
                                <tr>
                                    <td>{{ $p->package_name }}</td>
                                    <td>{{ $p->vehicle->vehicle_type }}</td>
                                    <td>{{ $p->price }}</td>
                                    <td>{{ $p->discount_percentage }}</td>
                                    <td>{{ $p->discounted_price }}</td>
                                    <td>{{ $p->active == 0 ? 'Not Active' : 'Active' }}</td>
                                    <td>
                                        <a href="{{ route('package/showForm', $p->package_id) }}"
                                            class="btn btn-warning btn-sm btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-edit"></i>
                                            </span><span class="text">Edit</span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

    @endsection
