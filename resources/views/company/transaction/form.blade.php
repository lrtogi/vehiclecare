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
            <form action="{{ route('transaction/save') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="transactionID"
                        value="{{ isset($model->transaction_id) ? $model->transaction_id : null }}">
                    <div class="form-group row">
                        <label for="vehicle_type" class="col-md-4 col-form-label">Vehicle Type : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select name="vehicle_type" id="select-vehicle_type" class="form-control" required>
                                <option value="" disabled selected hidden>Select Vehicle Type</option>
                                @foreach ($vehicleType as $v)
                                    <option value="{{ $v->vehicle_id }}"
                                        {{ isset($model->package_id) ? ($model->vehicle_id == $v->vehicle_id ? 'selected' : '') : '' }}>
                                        {{ $v->vehicle_type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="police_number" class="col-md-4 col-form-label ">Police Number : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="police_number" type="text"
                                value="{{ isset($model->police_number) ? $model->police_number : old('police_number') }}"
                                name="police_number" id="police_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="police_number" class="col-md-4 col-form-label ">Vehicle Name : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="vehicle_name" type="text"
                                value="{{ isset($model->vehicle_name) ? $model->vehicle_name : old('vehicle_name') }}"
                                name="vehicle_name" id="vehicle_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="package_name" class="col-md-4 col-form-label">Package Type : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select v-model="package_type" type="text" value="{{ old('package_type') }}"
                                name="package_type" id="package_type_selector" class="form-control">
                                <option value="">Select Package Type</option>
                                <option v-for="data in package_type_select.data" v-bind:value="data.package_id">
                                    ${data.package_name}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label ">Customer Name : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="customer_name" type="text"
                                value="{{ isset($model->customer_name) ? $model->customer_name : old('customer_name') }}"
                                name="customer_name" id="customer_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="order_date" class="col-md-4 col-form-label">Order Date : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="date" name="order_date" id="order_date" class="form-control"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label ">Total Price : </label>
                        <div class="col-md-6">
                            <input v-model="total_price" readonly type="text"
                                value="{{ isset($model->total_price) ? $model->total_price : old('total_price') }}"
                                name="total_price" id="total_price" class="form-control numajaDesimal price" required>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('transaction') }}" class="text-light btn btn-secondary">Back</a>
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
        var packageUrl = "{{ url('package/getByVehicle') }}";
        var packagePriceUrl = "{{ url('package/getPrice') }}";
        var app = new Vue({
            el: '#app',
            delimiters: ['${', '}'],
            data: {
                _token: "<?php echo csrf_token(); ?>",
                transactionID: "<?php echo isset($model->package_id) ? $model->package_id : null; ?>",
                package_type_select: [],
                package_type: "<?php echo isset($model->package_id) ? $model->package_id : old('package_type'); ?>",
                vehicle_type: "<?php echo isset($model->vehicle_id) ? $model->vehicle_id : old('vehicle_type'); ?>",
                vehicle_name: "<?php echo isset($model->vehicle_name) ? $model->vehicle_name : old('vehicle_name'); ?>",
                customer_name: "<?php echo isset($model->customer_name) ? $model->customer_name : old('customer_name'); ?>",
                police_number: "<?php echo isset($model->police_number) ? $model->police_number : old('police_number'); ?>",
                total_price: "<?php echo isset($model->total_price) ? $model->total_price : null; ?>",
                order_date: "<?php echo isset($model->order_date) ? $model->order_date : old('order_date'); ?>",
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
    <script type="text/javascript" src="{{ asset('js/transaction/transaction-form.js') }}"></script>
@endsection
