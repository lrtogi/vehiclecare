@extends('staff.master')

@section('title', 'Staff | Halaman Transaksi')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="container-fluid">
    <div class="card mb-4 py-3 border-left-primary">
        <div class="card-body text-dark">
            <h3>{{__('Halaman Transaksi')}}</h3>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Kendaraan</th>
                <th>Jam</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Kendaraan</th>
                <th>Jam</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </tfoot>
            <tbody>
                @foreach($statusKendaraan as $s)
                    <tr>
                        <td>{{ $s->id_customer }}</td>
                        <td>{{ $s->customers->nama_customer }}</td>
                        <td>{{ $s->histories->jenisKendaraan->nama_jenis }}</td>
                        <td>{{ $s->histories->jam }}</td>
                        <td>{{ $s->histories->tanggal }}</td>
                        <td>{{ $s->status }}</td>
                        <td><a href="{{ route('vehiclestatus.edit', $s->id_status) }}" class="btn btn-warning btn-sm btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span><span class="text">Edit</span></a></td>
                        <td><form action="{{ route('vehiclestatus.destroy',$s->id_status) }}" method="post">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm btn-icon-split" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span><span class="text">Hapus</span></button></form></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
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