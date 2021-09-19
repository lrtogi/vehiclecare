@extends('staff.master')

@section('title', 'Staff | Ubah Password')

@section('content')

<div class="container-fluid">
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif
    <div class="card mb-4 py-3 border-left-primary">
        <div class="card-body text-dark">
                <h3>{{__('Ubah Password')}}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Ubah Password</h4>
                    @if($errors->any())
                        <div class="alert alert-warning">
                            <ul>
                            @foreach($errors->all() as $error)
                                <li><p>{{ $error }}</p></li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <form action="{{ route('profile.store.password') }}" method="post">
                <div class="card-body">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                        <div class="col-md-6">
                            <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>

                        <div class="col-md-6">
                            <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                        </div>
                    </div>
                </div> 

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('staff.home') }}" class="text-light btn btn-secondary">Back</a>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav justify-content-end">
                                <input type="submit" class="text-light btn btn-primary" value="{{ __('Ubah Password') }}">
                            </ul>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection