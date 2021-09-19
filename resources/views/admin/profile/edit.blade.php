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
                <h3>{{__('Ubah Profile')}}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Ubah Profile</h4>
                </div>
                <form action="{{ route('profile.update') }}" method="post">
                <div class="card-body">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label">{{ __('Customer Name') }} : </label>
                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $customer->nama_customer }}" placeholder="Masukkan nama anda" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="tempatLahir" id="txttempatLahir" class="col-md-4 col-form-label">{{ __('Tempat Lahir') }} : </label>
                        
                        <div class="col-md-8">
                            <input type="text" name="tempat_lahir" value="{{ $customer->tempat_lahir }}" class="form-control @error('tempat_lahir') is-invalid @enderror" placeholder="Masukkan Tempat Lahir anda" id="tempat_lahir" required autocomplete="tempat_lahir">
                        
                            @error('tempat_lahir')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tanggal_lahir" id="txttanggalLahir" class="col-md-4 col-form-label">Tanggal Lahir : </label>
                        <div class="col-md-8">
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $customer->tanggal_lahir }}" class="form-control @error('tanggal_lahir') is-invalid @enderror" required autocomplete="tanggal_lahir">
                            
                            @error('tanggal_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jenisKelamin" id="txtjenisKelamin" class="col-md-4 col-form-label">Jenis Kelamin : </label>
                        <div class="col-md-8">
                            <div class="form-check">
                                <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror" type="radio" name="jenis_kelamin" value="Laki-Laki" required autocomplete="jenis_kelamin" {{ $customer->jenis_kelamin=="Laki-Laki" ? 'checked':'' }}>

                                <label id="jkLabelLaki" class="form-check-label" for="Laki-laki">
                                Laki-Laki
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" {{ $customer->jenis_kelamin =='Perempuan' ? 'checked':'' }}>
                                <label id="jkLabelPerempuan" class="form-check-label" for="Perempuan">
                                Perempuan
                                </label>                    
                            </div>
                            
                            @error('jenis_kelamin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>                
                    </div>

                    <div class="form-group row">
                        <label for="text" id="txtagama" class="col-md-4 col-form-label">Agama : </label>
                        <div class="col-md-8">
                            <select class="form-control @error('agama') is-invalid @enderror" name="agama" id="agama" style="width:100%" required auto-complete="agama">
                                <option value="" disabled selected hidden>Pilih agama anda</option>
                                <option value="Advent" {{ $customer->agama=="Advent" ? 'selected':'' }}>Advent</option>
                                <option value="Islam" {{ $customer->agama=='Islam' ? 'selected':'' }}>Islam</option>
                                <option value="Protestan" {{ $customer->agama=='Protestan' ? 'selected':'' }}>Protestan</option>
                                <option value="Budha" {{ $customer->agama=='Budha' ? 'selected':'' }}>Budha</option>
                                <option value="Hindu" {{ $customer->agama=='Hindu' ? 'selected':'' }}>Hindu</option>
                            </select>
                            @error('agama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="telepon" id="txttelepon" class="col-md-4 col-form-label">Telepon : </label>
                        <div class="col-md-8">
                            <input type="text" name="no_telp" value="{{ $customer->no_telp }}" placeholder="Masukkan nomor telepon anda" id="telepon" class="form-control @error('no_telp') is-invalid @enderror" required auto-complete="telepon">
                            @error('no_telp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alamat" id="txtalamat" class="col-md-4 col-form-label">Alamat : </label>
                        <div class="col-md-8">
                            <textarea placeholder="Masukkan alamat anda" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="8" required auto-complete="alamat">{{ $customer->alamat }}</textarea>
                            @error('alamat')
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
                            <a href="{{ route('staff.home') }}" class="text-light btn btn-secondary">Back</a>
                        </div>
                        <div class="col-md-9">
                            <div class="text-right">
                                <input type="submit" id="btnAddSave" class="text-light btn btn-primary" value="{{ __('Ubah Profile') }}">
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