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
                @if(session()->get('error'))
                    <div class="alert alert-danger">
                        <ul>
                            <li><p class="text-xs">{{ session()->get('error') }}</p></li>
                        </ul>
                    </div>
                @endif
                <form
                    class="user"
                    action="{{ route('register') }}"
                    method="post"
                >
                    {{ csrf_field() }}
                    <div class="form-group">
                    <input
                        value="{{ old('company_name') }}"
                        type="text"
                        class="form-control form-control-user @error('company_name') is-invalid @enderror"
                        name="company_name"
                        id="company_name"
                        placeholder="Company Name"
                        autocomplete="company_name"
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
                            value="{{ old('username') }}"
                            type="text"
                            class="form-control form-control-user @error('username') is-invalid @enderror"
                            name="username"
                            id="username"
                            placeholder="Username"
                            autocomplete="username"
                            required
                            autofocus
                        />
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        </div>
                    <div class="form-group">
                    <input
                        type="email"
                        class="form-control form-control-user @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        name="email"
                        id="email"
                        placeholder="Email Address" required
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
                        value="{{ old('password') }}"
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
                        value="{{ old('no_telp_company') }}"
                        type="text"
                        class="form-control form-control-user is_numeric @error('no_telp_company') is-invalid @enderror"
                        name="no_telp_company"
                        id="no_telp_company"
                        placeholder="No Telepon Perusahaan"
                        value="{{ old('no_telp_company') }}"
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
                            value="{{ old('no_telp') }}"
                            type="text"
                            class="form-control form-control-user is_numeric @error('no_telp') is-invalid @enderror"
                            name="no_telp"
                            id="no_telp"
                            placeholder="No Telepon"
                            value="{{ old('no_telp') }}"
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
                        value="{{ old('alamat') }}"
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
                    Register Company
                    </button>
                    <hr />
                    <a href="{{ url('/') }}" class="btn btn-danger btn-user btn-block">
                    <i class="fas fa-reply fa-fw"></i> Back to Main Menu
                    </a>
                </form>
                <hr />
                {{-- @if (Route::has('password.request'))
                    <div class="text-center">
                        <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                @endif --}}
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

@section('script')
<script>
    $(document).ready(function (){
        $('.is_numeric').keyup(function(e)
                                {
        if (/\D/g.test(this.value))
        {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
        });
    });
</script>

@endsection