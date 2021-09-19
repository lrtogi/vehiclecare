@extends('staff.master')

@section('title', 'Staff | Approve Pembayaran')

@section('content')

<div class="container-fluid">
    <div class="card mb-4 py-3 border-left-primary">
        <div class="card-body text-dark">
                <h3>{{__('Approve Pembayaran')}}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Approve Pembayaran</h4>
                </div>
                <form action="{{ route('approvePembayaran.update', $pembayaran->id_pembayaran) }}" method="post">
                    <div class="card-body">
                    {{ csrf_field() }}
                    @method('PATCH')
                        <div class="form-group row">
                            <label for="nama_customer" class="col-md-4 col-form-label">Nama Customer : </label>
                            <div class="col-md-8">
                                <input type="text" disabled name="nama_customer" id="nama_customer" class="form-control" value="{{ $pembayaran->customers->nama_customer }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tanggal_pembayaran" class="col-md-4 col-form-label">Tanggal Pembayaran : </label>
                            <div class="col-md-8">
                                <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control" value="{{ $pembayaran->tanggal_pembayaran }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="approve"  class="col-md-4 col-form-label">Approve : </label>
                            <div class="col-md-8">
                                <select class="form-control" name="approved" id="approved" style="width:100%">
                                    <option value="" disabled selected hidden>Pilih status</option>
                                    <option value="1" {{ $pembayaran->approved == 1 ? "selected":"" }}>Approve</option>
                                    <option value="0" {{ $pembayaran->approved == 0 ? "selected":"" }}>Not Approve</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="Tanggal" class="col-md-4 col-form-label">Tanggal : </label>
                            <div class="col-md-8">
                                <input type="date" disabled name="tanggal" id="tanggal" class="form-control" value="{{ $pembayaran->histories->tanggal }}">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="/staff/approvePembayaran" class="text-light btn btn-secondary">Approve Pembayaran</a>
                            </div>  
                            <div class="col-md-8">
                                <ul class="nav justify-content-end">
                                    <input type="submit" id="btnAddSave" class="text-light btn btn-primary" value="Ubah Pembayaran">
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <img src="{{ url('uploads/file/'.$pembayaran->file_pembayaran) }}" width="100%" height="400px">
        </div>
    </div>
</div>

@endsection