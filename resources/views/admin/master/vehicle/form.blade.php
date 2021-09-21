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
        @if($errors->any())
            <div class="alert alert-warning">
                <ul>
                @foreach($errors->all() as $error)
                    <li><p>{{ $error }}</p></li>
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin/vehicleType/save') }}" method="post" id="form-insert">
            <div class="card-body">
                {{ csrf_field() }}
                <input type="hidden" name="vehicleID" value="{{ isset($model->vehicle_id) ? $model->vehicle_id : null}}">
                <div class="form-group row">
                    <label for="vehicle_type" class="col-md-4 col-form-label">Vehicle Type : </label>
                    <div class="col-md-6">
                        <input v-model="vehicle_type" type="text" value="{{ old('vehicle_type') }}" name="vehicle_type" id="vehicle_type" class="form-control" data-validation="[NOTEMPTY]" data-validation-message="Input Vehicle Type">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('admin/vehicleType') }}" class="text-light btn btn-secondary">Back</a>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav justify-content-end">
                            <input @click="submitForm" type="submit" id="save-btn" class="text-light btn btn-primary" value="Save">
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
            kpiIDHidden: "<?php echo isset($model->vehicle_id) ? $model->vehicle_id : null; ?>",
            vehicle_type: <?php echo isset($model->vehicle_type) ? json_encode($model->vehicle_type) : json_encode(old('vehicle_type')); ?>
        },
        methods:{
            submitForm: function(){
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