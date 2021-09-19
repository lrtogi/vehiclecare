@extends('staff.master')
@section('title', 'Staff | Transaction')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="container-fluid">
    <div class="card mb-4 py-3 border-left-primary">
            <div class="card-body text-dark">
                  <h3>{{__('Halaman Tambah Transaksi')}}</h3>
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
        <form action="{{ route('transaction.store') }}" method="post">
            <div class="card-body">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="id_customer" class="col-md-4 col-form-label">ID Customer : </label>
                    <div class="col-md-6">
                        <input type="text" value="{{ old('id_customer') }}" name="id_customer" id="id_customer" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="btnSearchNamaCustomer" class="btn btn-success">Search</button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama_customer" class="col-md-4 col-form-label">Nama Customer : </label>
                    <div class="col-md-8">
                        <input type="text" disabled name="nama_customer" id="nama_customer" class="form-control" value="{{ old('nama_customer') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="jenis_kendaraan" class="col-md-4 col-form-label">Jenis Kendaraan : </label>
                    <div class="col-md-8">
                        <select class="form-control" name="id_jenis" id="id_jenis" style="width:100%">
                            <option value="" disabled selected hidden>Pilih Jenis Kendaraan</option>
                            @foreach($jenis_kendaraan as $jk)
                                <option value="{{ $jk->id_jenis }}" {{ old('id_jenis')==$jk->id_jenis? 'selected':'' }}>{{ $jk->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tanggal_booking" class="col-md-4 col-form-label">Tanggal : </label>
                    <div class="col-md-8">
                        <input type="date" name="tanggal" id="tanggal" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="text" class="col-md-4 col-form-label">Jam : </label>
                    <div class="col-md-8">
                        <select class="form-control" name="jam" id="jam" style="width:100%">
                            <option value="" disabled selected hidden>Pilih Jam Pengerjaan</option>
                            @while($jam<=20):
                                <option value='{{ $jam }}' {{ old('jam')==$jam? 'selected':'' }}>{{ $jam }} : 00</option>
                                {{$jam++}}
                            @endwhile
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="harga" class="col-md-4 col-form-label">Harga : </label>
                    <div class="col-md-8">
                        <input type="text" name="harga" id="harga" class="form-control" disabled>
                    </div>
                </div>
                
            </div> 
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
                        <a href="/staff/transaction" class="text-light btn btn-secondary">Transaction</a>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav justify-content-end">
                            <input type="submit" id="btnAddSave" class="text-light btn btn-primary" value="Tambah Transaksi">
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')

<script>
$(document).ready(function(){
    $(document).on('click','#btnSearchNamaCustomer',function(){
        var value=$('#id_customer').val();
        console.log(value);
        $.get("{{ url('/staff/transaction/search') }}",{
            'search':value
          },
          function(data,status){
            $('#nama_customer').val(data);
            console.log(data);
          });
    });
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

    var today = new Date();
    document.getElementById("tanggal").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);

    $('#id_jenis').change(function(){
        var id = "";
        $("#id_jenis option:selected").each(function(){
            id+=$(this).val();
        });
        $.get("{{ url('/staff/transaction/hargaJenisKendaraan') }}",{
            'id_jenis':id
          },
          function(data,status){
            $('#harga').val(data);
            console.log(data);
          });

    });
});
</script>

@endsection