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
            <form action="{{ route('package/save') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="packageID"
                        value="{{ isset($model->package_id) ? $model->package_id : null }}">
                    <div class="form-group row">
                        <label for="package_name" class="col-md-4 col-form-label">Package Name : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="package_name" type="text"
                                value="{{ isset($model->package_id) ? $model->package_name : old('package_name') }}"
                                name="package_name" id="package_name" class="form-control" data-validation="[NOTEMPTY]"
                                data-validation-message="Input Vehicle Type" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vehicle_type" class="col-md-4 col-form-label">Vehicle Type : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select name="vehicle_type" id="vehicle_type" class="form-control" required>
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
                        <label for="username" class="col-md-4 col-form-label ">Base Price : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input v-model="price" type="text"
                                value="{{ isset($model->price) ? $model->price : old('price') }}" name="price" id="price"
                                class="form-control numajaDesimal price" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label ">Discount Percentage Price : </label>
                        <div class="col-md-6">
                            <input v-model="discount_percentage" min=0 max=100 type="text"
                                value="{{ isset($model->discount_percentage) ? $model->discount_percentage : old('discount_percentage') }}"
                                name="discount_percentage" id="discount_percentage"
                                class="form-control numajaDesimal percent" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label ">Discounted Price : </label>
                        <div class="col-md-6">
                            <input v-model="discounted_price" readonly type="text"
                                value="{{ isset($model->discounted_price) ? $model->discounted_price : old('discounted_price') }}"
                                name="discounted_price" id="discounted_price" class="form-control numajaDesimal price"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="active" class="col-md-4 col-form-label">Active : </label>
                        <div class="col-md-6">
                            <select name="active" id="active" class="form-control">
                                <option value="1"
                                    {{ isset($model->package_id) ? ($model->active == 1 ? 'selected' : '') : '' }}>
                                    Active</option>
                                <option value="0"
                                    {{ isset($model->package_id) ? ($model->active == 0 ? 'selected' : '') : '' }}>Not
                                    Active</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('package') }}" class="text-light btn btn-secondary">Back</a>
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
        $(document).on('blur', ".percent", function(e) {
            var id = $(this).attr('id');
            if ($(this).val() != '') {
                var num = parseFloat($(this).val());
                if (num > 100) num = 100;
                $(this).val(num.toFixed(2));
                var price = $('#price').val();
                var discount_percentage = $('#discount_percentage').val();
                var discounted_price = price - (price * discount_percentage / 100);
                $('#discounted_price').val(discounted_price);
            }
        });
        $(document).ready(function() {
            $(".numajaDesimal").keypress(function(e) {
                if ((e.charCode >= 48 && e.charCode <= 57) || (e.charCode == 0) || (e.charCode == 46))
                    return true;
                else
                    return false;
            });
            $('#price').keyup(function() {
                var price = $('#price').val();
                var discount_percentage = $('#discount_percentage').val();
                var discounted_price = price - (price * discount_percentage / 100);
                $('#discounted_price').val(discounted_price);
            });
            $('#discount_percentage').keyup(function() {
                var price = $('#price').val();
                var discount_percentage = $('#discount_percentage').val();
                var discounted_price = price - (price * discount_percentage / 100);
                $('#discounted_price').val(discounted_price);
            });

            $('#price').change(function() {
                var price = $('#price').val();
                var discount_percentage = $('#discount_percentage').val();
                var discounted_price = price - (price * discount_percentage / 100);
                $('#discounted_price').val(discounted_price);
            });
            $('#discount_percentage').change(function() {
                var price = $('#price').val();
                var discount_percentage = $('#discount_percentage').val();
                var discounted_price = price - (price * discount_percentage / 100);
                $('#discounted_price').val(discounted_price);
            });

        });


        var app = new Vue({
            el: '#app',
            delimiters: ['${', '}'],
            data: {
                _token: "<?php echo csrf_token(); ?>",
                packageID: "<?php echo isset($model->package_id) ? $model->package_id : null; ?>",
                package_name: "<?php echo isset($model->package_name) ? $model->package_name : old('package_name'); ?>",
                vehicle_type: "<?php echo isset($model->vehicle_id) ? $model->vehicle_id : null; ?>",
                price: "<?php echo isset($model->price) ? $model->price : null; ?>",
                discount_percentage: "<?php echo isset($model->discount_percentage) ? $model->discount_percentage : null; ?>",
                discounted_price: "<?php echo isset($model->discounted_price) ? $model->discounted_price : null; ?>",
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
