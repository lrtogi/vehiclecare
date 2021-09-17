@extends('layouts.master')

@section('title', 'Vehicle Care | Register')

@section('content')
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
            <div class="col-lg-7">
                <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                </div>
                <form
                    class="user"
                    action="{{ route('register') }}"
                    method="post"
                >
                    {{ csrf_field() }}
                    <div class="form-group">
                    <input
                        value="{{ old('name') }}"
                        type="text"
                        class="form-control form-control-user @error('name') is-invalid @enderror"
                        name="name"
                        id="name"
                        placeholder="Customer Name"
                        autocomplete="name"
                        required
                        autofocus
                    />
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    </div>
                    <div class="form-group">
                    <input
                        type="email"
                        class="form-control form-control-user @error('email') is-invalid @enderror"
                        name="email"
                        id="email"
                        placeholder="Email Address"
                    />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input
                        type="password"
                        class="form-control form-control-user @error('password') is-invalid @enderror"
                        name="password"
                        id="password"
                        value="{{ old('email') }}"
                        placeholder="Password"
                        required
                        autocomplete="email"
                        />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="col-sm-6">
                        <input
                        type="password"
                        class="form-control form-control-user"
                        id="password-confirm"
                        name="password_confirmation"
                        placeholder="Repeat Password"
                        required 
                        autocomplete="new-password"
                        />
                    </div>
                    </div>
                    <div class="form-group">
                    <input
                        value="{{ old('tempat_lahir') }}"
                        type="text"
                        class="form-control form-control-user @error('tempat_lahir') is-invalid @enderror"
                        name="tempat_lahir"
                        id="tempat_lahir"
                        placeholder="Tempat lahir"
                        required
                    />
                    @error('tempat_lahir')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="form-group row">
                    <div class="col-md-3 mt-1 mb-3 mb-sm-0">
                        <label class="col-form-label">Tanggal Lahir : </label>
                    </div>
                    <div class="col-md-9">
                        <input
                        value="{{ old('tanggal_lahir') }}"
                        type="date"
                        class="form-control form-control-user @error('tanggal_lahir') is-invalid @enderror"
                        name="tanggal_lahir"
                        id="tanggal_lahir"
                        placeholder="Tanggal lahir"
                        required
                        />
                        @error('tanggal_lahir')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group row">
                    <label
                        for="jenisKelamin"
                        id="txtjenisKelamin"
                        class="col-md-3 col-form-label"
                        >Jenis Kelamin :
                    </label>
                    <div class="col-md-9">
                        <div class="form-check pt-2">
                        <input
                            class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                            type="radio"
                            name="jenis_kelamin"
                            value="Laki-Laki"
                            required
                            autocomplete="jenis_kelamin"
                            required
                            {{ old('jenis_kelamin')=="Laki-Laki" ? 'checked':'' }}
                        />

                        <label
                            id="jkLabelLaki"
                            class="form-check-label"
                            for="Laki-laki"
                        >
                            Laki-Laki
                        </label>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="jenis_kelamin"
                            value="Perempuan"
                            {{ old('jenis_kelamin')=="Perempuan" ? 'checked':'' }}
                            required
                        />
                        <label
                            id="jkLabelPerempuan"
                            class="form-check-label"
                            for="Perempuan"
                        >
                            Perempuan
                        </label>
                        @error('jenis_kelamin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    </div>
                    <div class="form-group row">
                    <label
                        for="text"
                        id="txtagama"
                        class="col-md-3 col-form-label"
                        >Agama :
                    </label>
                    <div class="col-md-9">
                        <select
                        class="form-control rounded @error('agama') is-invalid @enderror"
                        name="agama"
                        id="agama"
                        style="width: 100%;"
                        required
                        auto-complete="agama"
                        >
                        <option value="" disabled selected hidden
                            >Pilih agama anda</option
                        >
                        <option value="Advent" {{ old('agama')=="Advent" ? 'selected':'' }}>Advent</option>
                        <option value="Islam" {{ old('agama')=="Islam" ? 'selected':'' }}>Islam</option>
                        <option value="Protestan" {{ old('agama')=="Protestan" ? 'selected':'' }}>Protestan</option>
                        <option value="Budha" {{ old('agama')=="Budha" ? 'selected':'' }}>Budha</option>
                        <option value="Hindu" {{ old('agama')=="Hindu" ? 'selected':'' }}>Hindu</option>
                        </select>
                        @error('agama')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group">
                    <input
                        value="{{ old('no_telp') }}"
                        type="text"
                        class="form-control form-control-user @error('no_telp') is-invalid @enderror"
                        name="no_telp"
                        id="no_telp"
                        placeholder="No Telepon"
                        required
                    />
                    @error('no_telp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="form-group">
                    <input
                        value="{{ old('alamat') }}"
                        type="text"
                        class="form-control form-control-user @error('alamat') is-invalid @enderror"
                        name="alamat"
                        id="alamat"
                        placeholder="Alamat / Lokasi perusahaan"
                        required
                    />
                    @error('alamat')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <button type="submit"
                    href="login.html"
                    class="btn btn-primary btn-user btn-block"
                    >
                    Register Account
                    </button>
                    <hr />
                    <a href="{{ url('/') }}" class="btn btn-danger btn-user btn-block">
                    <i class="fas fa-reply fa-fw"></i> Back to Main Menu
                    </a>
                </form>
                <hr />
                <div class="text-center">
                    <a class="small" href="forgot-password.html"
                    >Forgot Password?</a
                    >
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}"
                    >Already have an account? Login!</a
                    >
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
@endsection