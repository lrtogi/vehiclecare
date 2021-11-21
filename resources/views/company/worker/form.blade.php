@extends(auth()->user()->user_type == 3 ? 'admin.master' : 'company.master')

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

    <div class="container-fluid" id="app">
        <div class="card mb-4 py-3 border-left-primary">
            <div class="card-body text-dark">
                <h3>{{ $pageTitle }}</h3>
            </div>
        </div>
        <div class="card">
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
            <form action="{{ route('worker/save') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    @if (!isset($model->worker_id))
                        <div class="form-group row">
                            <label for="full_name" class="col-md-4 col-form-label">Full Name : </label>
                            <div class="col-md-6">
                                <input type="text" value="{{ old('full_name') }}" name="full_name" id="full_name"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label">Username : </label>
                            <div class="col-md-6">
                                <input type="text" value="{{ old('username') }}" name="username" id="username"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">Email : </label>
                            <div class="col-md-6">
                                <input type="text" value="{{ old('email') }}" name="email" id="email"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_telp" class="col-md-4 col-form-label">No Telp : </label>
                            <div class="col-md-6">
                                <input type="text" value="{{ old('no_telp') }}" name="no_telp" id="no_telp"
                                    class="form-control is_numeric">
                            </div>
                        </div>
                        @if (!isset($model->id))
                            <div class="form-group row">
                                <label for="password" class="col-md-4 ">Password : </label>
                                <div class="col-md-3">
                                    <input type="password" value="{{ old('password') }}" name="password"
                                        placeholder="Password" id="password" class="form-control select2">
                                </div>
                                <div class="col-md-3">
                                    <input v-model="password_confirmation" type="password"
                                        value="{{ old('password_confirmation') }}" name="password_confirmation"
                                        placeholder="Re-type Password" id="password_confirmation"
                                        class="form-control select2">
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="alamat" class="col-md-4 col-form-label">Alamat : </label>
                            <div class="col-md-6">
                                <input type="text" value="{{ old('alamat') }}" name="alamat" id="alamat"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="has_access" class="col-md-4 col-form-label">Has Web App Access : </label>
                            <div class="col-md-6">
                                <select name="has_access" id="has_access" class="form-control">
                                    <option value="" hidden selected disabled>Select Access</option>
                                    <option value="1" {{ $model->user_type == 1 ? 'selected' : '' }}>No Access</option>
                                    <option value="2" {{ $model->user_type == 2 ? 'selected' : '' }}>Has Access</option>
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="workerID"
                            value="{{ isset($model->worker_id) ? $model->worker_id : null }}">
                        <div class="form-group row">
                            <label for="full_name" class="col-md-4 col-form-label">Full Name : </label>
                            <div class="col-md-6">
                                <input v-model="worker_name" type="text"
                                    value="{{ isset($model->worker_id) ? $model->worker_name : old('full_name') }}"
                                    name="full_name" id="full_name" class="form-control" data-validation="[NOTEMPTY]"
                                    data-validation-message="Input Full Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_telp" class="col-md-4 col-form-label">No Telp : </label>
                            <div class="col-md-6">
                                <input v-model="no_telp" type="text"
                                    value="{{ isset($model->worker_id) ? $model->no_telp : old('no_telp') }}"
                                    name="no_telp" id="no_telp" class="form-control is_numeric" data-validation="[NOTEMPTY]"
                                    data-validation-message="Input No Telepon">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat" class="col-md-4 col-form-label">Alamat : </label>
                            <div class="col-md-6">
                                <input type="text"
                                    value="{{ isset($model->worker_id) ? $model->alamat : old('alamat') }}" name="alamat"
                                    id="alamat" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="has_access" class="col-md-4 col-form-label">Has Web App Access : </label>
                            <div class="col-md-6">
                                <select name="has_access" id="has_access" class="form-control">
                                    <option value="" hidden selected disabled>Select Access</option>
                                    <option value="1" {{ $model->user_type == 1 ? 'selected' : '' }}>No Access</option>
                                    <option value="2" {{ $model->user_type == 2 ? 'selected' : '' }}>Has Access</option>
                                </select>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('worker') }}" class="text-light btn btn-secondary">Back</a>
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
        var app = new Vue({
            el: '#app',
            delimiters: ['${', '}'],
            data: {
                _token: "<?php echo csrf_token(); ?>",
                workerID: "<?php echo isset($model->worker_id) ? $model->worker_id : null; ?>",
                worker_name: "<?php echo isset($model->worker_name) ? $model->worker_name : old('full_name'); ?>",
                no_telp: "<?php echo isset($model->no_telp) ? $model->no_telp : null; ?>",
                alamat: "<?php echo isset($model->alamat) ? $model->alamat : null; ?>",
                has_access: "<?php echo isset($model->has_access) ? $model->has_access : null; ?>"
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

@endsection
