@extends('admin.master')
@section('title', $pageTitle)

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
            <form action="{{ route('admin/company/save') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="company_name" class="col-md-4 col-form-label">Company Name : </label>
                        <div class="col-md-6">
                            <input v-model="company_name" type="text" value="{{ old('company_name') }}"
                                name="company_name" id="company_name" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input Vehicle Type">
                        </div>
                    </div>
                    <div class="user-form">
                        <div class="form-group row new-user">
                            <label for="email" class="col-md-4 col-form-label">Email PIC : </label>
                            <div class="col-md-6">
                                <input v-model="user_email" type="text" value="{{ old('email') }}" name="email" id="email"
                                    class="form-control select2">
                            </div>
                            <div class="form-check">
                                <input type="hidden" class="form-check-input" value="0" name="useExistingEmail1"
                                    id="useExistingEmail2">
                                <input type="checkbox" class="form-check-input" value="1" name="useExistingEmail1"
                                    id="useExistingEmail1">
                                <label class="form-check-label" for="useExistingEmail1">Use Current User</label>
                            </div>
                        </div>
                        <div class="form-group row current-user">
                            <label for="email" class="col-md-4 col-form-label">Email PIC : </label>
                            <div class="col-md-6">
                                <select v-model="user_email" type="text" value="{{ old('email') }}" name="email"
                                    id="user_email_selector" class="form-control select2">
                                    <option value="">Select user email</option>
                                    <option v-for="data in user_email_select.data" v-bind:value="data.email">
                                        ${data.email}|${data.username}
                                    </option>
                                </select>
                            </div>
                            <div class="form-check">
                                <input type="hidden" class="form-check-input" value="0" name="useExistingEmail2"
                                    id="useExistingEmail2">
                                <input type="checkbox" class="form-check-input" value="1" name="useExistingEmail2"
                                    id="useExistingEmail2">
                                <label class="form-check-label" for="useExistingEmai2">Use Current User</label>
                            </div>
                        </div>
                        <div class="form-group row new-user">
                            <label for="full_name" class="col-md-4 col-form-label">Full Name : </label>
                            <div class="col-md-6">
                                <input v-model="full_name" type="text" value="{{ old('full_name') }}" name="full_name"
                                    id="full_name" class="form-control select2">
                            </div>
                        </div>
                        <div class="form-group row new-user">
                            <label for="username" class="col-md-4 col-form-label">Username : </label>
                            <div class="col-md-6">
                                <input v-model="username" type="text" value="{{ old('username') }}" name="username"
                                    id="username" class="form-control select2">
                            </div>
                        </div>
                        <div class="form-group row new-user">
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
                        <div class="form-group row new-user">
                            <label for="username" class="col-md-4 col-form-label ">Phone Number : </label>
                            <div class="col-md-6">
                                <input v-model="no_telp" type="text" value="{{ old('username') }}" name="no_telp"
                                    id="no_telp" class="form-control is_numeric">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label">Company Address : </label>
                        <div class="col-md-6">
                            <input v-model="alamat_perusahaan" type="text" value="{{ old('alamat_perusahaan') }}"
                                name="alamat_perusahaan" id="alamat_perusahaan" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label ">Company Phone Number : </label>
                        <div class="col-md-6">
                            <input v-model="no_telp_company" type="text" value="{{ old('no_telp_company') }}"
                                name="no_telp_company" id="no_telp_company" class="form-control is_numeric">
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('admin/company') }}" class="text-light btn btn-secondary">Back</a>
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
    @endsection

    @section('script')
        <script type="text/javascript">
            var getUserUrl = "{{ url('admin/company/getUser') }}";
            var app = new Vue({
                el: '#app',
                delimiters: ['${', '}'],
                data: {
                    _token: "<?php echo csrf_token(); ?>",
                    company_name: '',
                    user_email: "<?php echo isset($model->pic_email) ? $model->pic_email : null; ?>",
                    user_email_select: []
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
        <script type="text/javascript" src="{{ asset('js/master/company-form.js') }}"></script>
    @endsection
