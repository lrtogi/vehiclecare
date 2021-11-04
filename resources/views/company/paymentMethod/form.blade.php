@extends('company.master')

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
            <form action="{{ route('paymentMethod/save') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="paymentMethodID"
                        value="{{ isset($model->payment_method_id) ? $model->payment_method_id : null }}">
                    <div class="form-group row">
                        <label for="method" class="col-md-4 col-form-label">Method Type : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="method" type="text"
                                value="{{ isset($model->method) ? $model->method : old('method') }}" name="method"
                                id="method" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input Method Type" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="value" class="col-md-4 col-form-label">Value : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="value" type="text"
                                value="{{ isset($model->value) ? $model->value : old('value') }}" name="value" id="value"
                                class="form-control" data-validation="[NOTEMPTY]" data-validation-message="Input Value"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="on_behalf_of" class="col-md-4 col-form-label">On Behalf Of : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="on_behalf_of" type="text"
                                value="{{ isset($model->on_behalf_of) ? $model->on_behalf_of : old('on_behalf_of') }}"
                                name="on_behalf_of" id="on_behalf_of" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input On Behalf Of" required>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('paymentMethod') }}" class="text-light btn btn-secondary">Back</a>
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
        $(document).ready(function() {

        });

        var app = new Vue({
            el: '#app',
            delimiters: ['${', '}'],
            data: {
                _token: "<?php echo csrf_token(); ?>",
                paymentMethodID: "<?php echo isset($model->payment_method_id) ? $model->payment_method_id : null; ?>",
                method: "<?php echo isset($model->method) ? $model->method : old('method'); ?>",
                value: "<?php echo isset($model->value) ? $model->value : old('value'); ?>",
                on_behalf_of: "<?php echo isset($model->on_behalf_of) ? $model->on_behalf_of : old('on_behalf_of'); ?>",
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
