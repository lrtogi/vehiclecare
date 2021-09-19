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
                <th>Harga</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Add Nota</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Kendaraan</th>
                <th>Jam</th>
                <th>Tanggal</th>
                <th>Harga</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Add Nota</th>
            </tr>
            </tfoot>
            <tbody>
                @foreach($history as $h)
                    <tr>
                        <td>{{ $h->id_customer }}</td>
                        <td>{{ $h->customers->nama_customer }}</td>
                        <td>{{ $h->jenisKendaraan->nama_jenis }}</td>
                        <td>{{ $h->jam }}</td>
                        <td>{{ $h->tanggal }}</td>
                        <td>{{ $h->jenisKendaraan->harga }}</td>
                        <td><a href="{{ route('transaction.edit', $h->id_history) }}" class="btn btn-warning btn-sm btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span><span class="text">Edit</span></a></td>
                        <td><form action="{{ route('transaction.destroy',$h->id_history) }}" method="post">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm btn-icon-split" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span><span class="text">Hapus</span></button></form></td>
                        <td><a href="{{ route('notas.create', $h->id_history) }}" class="btn btn-warning btn-sm btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span><span class="text">Add Pembayaran</span></a></td>
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