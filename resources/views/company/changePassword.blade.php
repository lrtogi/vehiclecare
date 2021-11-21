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
            <form action="{{ route('changePassword') }}" method="post" id="form-insert">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="packageID"
                        value="{{ isset($model->package_id) ? $model->package_id : null }}">
                    <div class="form-group row">
                        <label for="package_name" class="col-md-4 col-form-label">Old Password : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label">New Password : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-4 col-form-label">Confirmation Password : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('home') }}" class="text-light btn btn-secondary">Back</a>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav justify-content-end">
                                <input type="submit" id="save-btn" class="text-light btn btn-primary" value="Save">
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
    </script>

@endsection
