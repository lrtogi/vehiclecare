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
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-pager margbot20 none-usul">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label">Order Date</label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label"><b>{{ $model->order_date }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label">Customer Name</label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label"><b>{{ $model->customer_name }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label">Police Number</label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label"><b>{{ $model->police_number }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label">Vehicle Name</label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left word-straight">
                                                <label class="col-form-label"><b>{{ $model->vehicle_name }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margbot20">
                                <div class="col-md-7">
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="text-left">
                                                <label class="col-form-label">Queue No. </label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left">
                                                <label class="col-form-label"><b>{{ $model->index }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="text-left">
                                                <label class="col-form-label">Total Price</label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left">
                                                <label class="col-form-label"><b>{{ $model->total_price }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row input-wrapper margbot10">
                                        <div class="col-md-3">
                                            <p class="text-left">
                                                <label class="col-form-label">Status</label>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="nomarg text-left">
                                                <label class="col-form-label"><b>{{ $model->status }}</b></label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <a href="{{ route('transaction/print', $model->transaction_id) }}" target="_blank"
                                class="text-light btn btn-success"><b>Print</b> <i class="fa fa-print"></i></a>
                        </ul>
                    </div>
                </div>
            </div>
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
