@extends('staff.master')

@section('title', 'Staff | Pembayaran')

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
            <h3>{{__('Halaman Pembayaran')}}</h3>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Nota</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>ID Pembayaran</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Pembayaran</th>
                <th>Approved</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>ID Pembayaran</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Pembayaran</th>
                <th>Approved</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </tfoot>
            <tbody>
                @foreach($pembayaran as $p)
                    <tr>
                        <th>{{ $p->id_pembayaran }}</th>
                        <th>{{ $p->customers->nama_customer }}</th>
                        <th>{{ $p->tanggal_pembayaran }}</th>
                        <th class="{{ $p->approved == 1 ? 'text-success' : 'text-danger' }}">{{ $p->approved==1 ? 'Approved' : 'Not Approved' }}</th>
                        <td><a href="{{ route('approvePembayaran.edit', $p->id_pembayaran) }}" class="btn btn-warning btn-sm btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span><span class="text">Edit</span></a></td>
                        <td><form action="{{ route('approvePembayaran.destroy',$p->id_pembayaran) }}" method="post">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm btn-icon-split" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
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