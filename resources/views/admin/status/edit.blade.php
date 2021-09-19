@extends('staff.master')

@section('title', 'Staff | Ubah Profile')

@section('content')

<div class="container-fluid">
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif
    <div class="card mb-4 py-3 border-left-primary">
        <div class="card-body text-dark">
                <h3>{{__('Ubah Status Kendaraan')}}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Ubah Status Kendaraan</h4>
                </div>
                <form action="{{ route('vehiclestatus.update', $statusKendaraan->id_status) }}" method="post">
                <div class="card-body">
                    {{ csrf_field() }}
                    @method('PATCH') 
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label">{{ __('Customer Name') }} : </label>
                        <div class="col-md-8">
                            <input id="name" type="text" disabled class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $statusKendaraan->customers->nama_customer }}" placeholder="Masukkan nama anda">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="tanggal_lahir" id="txttanggalLahir" class="col-md-4 col-form-label">Tanggal Lahir : </label>
                        <div class="col-md-8">
                            <input type="date" disabled name="tanggal" id="tanggal" value="{{ $statusKendaraan->histories->tanggal }}" class="form-control">
                            
                            @error('tanggal_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jam" class="col-md-4 col-form-label">Jam : </label>
                        <div class="col-md-8">
                            <input type="text" disabled name="no_telp" value="{{ $statusKendaraan->histories->jam }}" id="telepon" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text" class="col-md-4 col-form-label">Status : </label>
                        <div class="col-md-8">
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" style="width:100%" required>
                                <option value="" disabled selected hidden>Pilih status</option>
                                <option value="Menunggu Kendaraan" {{ $statusKendaraan->status=='Menunggu Kendaraan' ? 'selected':'' }}>Menunggu Kendaraan</option>
                                <option value="Sedang Dicuci" {{ $statusKendaraan->status=='Sedang Dicuci' ? 'selected':'' }}>Sedang Dicuci</option>
                                <option value="Done" {{ $statusKendaraan->status=='Done' ? 'selected':'' }}>Selesai</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div> 

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('statuskendaraan.index') }}" class="text-light btn btn-secondary">Back to Status Kendaraan</a>
                        </div>
                        <div class="col-md-9">
                            <div class="text-right">
                                <input type="submit" id="btnAddSave" class="text-light btn btn-primary" value="{{ __('Ubah Status') }}">
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection