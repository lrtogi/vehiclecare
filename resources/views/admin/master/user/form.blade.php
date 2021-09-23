@extends('admin.master')

@section('content')

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
        <div class="card mb-4 py-3 border-left-primary">
            <div class="card-body text-dark">
                <h3>{{ $pageTitle }}</h3>
            </div>
        </div>
        <div class="card" id="app">
            @if ($errors->any())
                <div class="alert alert-warning">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                <p>{{ $error }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin/user/save') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ isset($model->id) ? $model->id : null }}">
                    <div class="form-group row">
                        <label for="full_name" class="col-md-4 col-form-label">Full Name : </label>
                        <div class="col-md-6">
                            <input v-model="full_name" type="text"
                                value="{{ isset($model->id) ? $model->customer_name : old('full_name') }}"
                                name="full_name" id="full_name" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input Full Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label">Username : </label>
                        <div class="col-md-6">
                            <input v-model="username" type="text"
                                value="{{ isset($model->id) ? $model->username : old('username') }}" name="username"
                                id="username" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input Username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label">Email : </label>
                        <div class="col-md-6">
                            <input v-model="email" type="text"
                                value="{{ isset($model->id) ? $model->email : old('email') }}" name="email" id="email"
                                class="form-control" data-validation="[NOTEMPTY]" data-validation-message="Input Email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="no_telp" class="col-md-4 col-form-label">No Telp : </label>
                        <div class="col-md-6">
                            <input v-model="no_telp" type="text"
                                value="{{ isset($model->id) ? $model->no_telp : old('no_telp') }}" name="no_telp"
                                id="no_telp" class="form-control is_numeric" data-validation="[NOTEMPTY]"
                                data-validation-message="Input No Telepon">
                        </div>
                    </div>
                    @if (!isset($model->id))
                        <div class="form-group row">
                            <label for="password" class="col-md-4 ">Password : </label>
                            <div class="col-md-3">
                                <input v-model="password" type="password" value="{{ old('password') }}" name="password"
                                    placeholder="Password" id="password" class="form-control select2">
                            </div>
                            <div class="col-md-3">
                                <input v-model="password_confirmation" type="password"
                                    value="{{ old('password_confirmation') }}" name="password_confirmation"
                                    placeholder="Re-type Password" id="password_confirmation" class="form-control select2">
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="alamat" class="col-md-4 col-form-label">Alamat : </label>
                        <div class="col-md-6">
                            <input v-model="alamat" type="text"
                                value="{{ isset($model->id) ? $model->alamat : old('alamat') }}" name="alamat"
                                id="alamat" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input alamat">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_type" class="col-md-4 col-form-label">User Type : </label>
                        <div class="col-md-6">
                            <select name="user_type" id="user_type" class="form-control">
                                <option value="" hidden selected disabled>Select User Type</option>
                                <option value="0" {{ $model->user_type == 0 ? 'selected' : '' }}>Customer</option>
                                <option value="1" {{ $model->user_type == 1 ? 'selected' : '' }}>Worker</option>
                                <option value="2" {{ $model->user_type == 2 ? 'selected' : '' }}>PIC Company</option>
                                <option value="3" {{ $model->user_type == 3 ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row need-company">
                        <label for="company_id" class="col-md-4 col-form-label">Company ID : </label>
                        <div class="col-md-6">
                            <select name="company_id" id="company_id" class="form-control select2">
                                <option value="" hidden>Select Company</option>
                                @foreach ($company as $c)
                                    <option value="{{ $c->company_id }}"
                                        {{ $model->company_id == $c->company_id ? 'selected' : '' }}>
                                        {{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="locked" class="col-md-4 col-form-label">Locked : </label>
                        <div class="col-md-6">
                            <select name="locked" id="locked" class="form-control">
                                <option value="" hidden selected disabled>Select Locked</option>
                                <option value="0" {{ $model->locked == 0 ? 'selected' : '' }}>Not Locked</option>
                                <option value="1" {{ $model->locked == 1 ? 'selected' : '' }}>Locked</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('admin/user') }}" class="text-light btn btn-secondary">Back</a>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav justify-content-end">
                                <input @click="submitForm" type="submit" id="save-btn" class="text-light btn btn-primary"
                                    value="Save">
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var user_id = "<?php echo isset($model->id) ? $model->id : null; ?>";
        var app = new Vue({
            el: '#app',
            delimiters: ['${', '}'],
            data: {
                _token: "<?php echo csrf_token(); ?>",
                full_name: "<?php echo isset($model->customer_name) ? $model->customer_name : null; ?>",
                user_id: "<?php echo isset($model->id) ? $model->id : null; ?>",
                username = "<?php echo isset($model->username) ? $model->username : null; ?>",
                email: "<?php echo isset($model->email) ? $model->email : null; ?>",
                no_telp: "<?php echo isset($model->no_telp) ? $model->no_telp : null; ?>",
                alamat: "<?php echo isset($model->alamat) ? $model->alamat : null; ?>"
            },
            methods: {
                submitForm: function() {
                    var input = $('#form-insert').find('input[data-validation="[NOTEMPTY]"]');
                    var isInputEmpty = true;
                    for (var i = 0; i < input.length; i++) {
                        if (!/\S/.test($(input[i]).val())) {
                            isInputEmpty = false;
                        }
                    }
                    var inputmix = $('#form-insert').find('input[data-validation="[NOTEMPTY, MIXED]"]');
                    for (var i = 0; i < inputmix.length; i++) {
                        if (!/^[\w\s-]+$/.test($(inputmix[i]).val()) || !/\S/.test($(inputmix[i]).val())) {
                            isInputEmpty = false;
                        }
                    }

                    if (isInputEmpty) {
                        var btnLoading = Ladda.create(document.querySelector('#save-btn'));
                        btnLoading.start();
                        $('#form-insert').submit();
                    }
                }
            }
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/master/user-form.js') }}"></script>

@endsection
