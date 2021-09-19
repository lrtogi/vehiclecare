@extends('staff.master')

@section('title','Staff | Edit Transaction')

@section('content')


<div class="container-fluid">
    <div class="card mb-4 py-3 border-left-primary">
            <div class="card-body text-dark">
                  <h3>{{__('Halaman Edit Transaksi')}}</h3>
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
        <form action="{{ route('transaction.update', $history->id_history) }}" method="post">
            <div class="card-body">
                {{ csrf_field() }}
                @method('PATCH') 
                <div class="form-group row">
                    <label for="id_customer" class="col-md-4 col-form-label">ID Customer : </label>
                    <div class="col-md-6">
                        <input type="text" value="{{ $history->id_customer }}" name="id_customer" id="id_customer" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="btnSearchNamaCustomer" class="btn btn-success">Search</button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama_customer" class="col-md-4 col-form-label">Nama Customer : </label>
                    <div class="col-md-8">
                        <input type="text" disabled name="nama_customer" id="nama_customer" class="form-control" value="{{ $history->customers->nama_customer }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="jenis_kendaraan" class="col-md-4 col-form-label">Jenis Kendaraan : </label>
                    <div class="col-md-8">
                        <select class="form-control" name="id_jenis" id="id_jenis" style="width:100%">
                            <option value="" disabled selected hidden>Pilih Jenis Kendaraan</option>
                            @foreach($jenis_kendaraan as $jk)
                            <option value="{{ $jk->id_jenis }}" {{ $history->id_jenis == $jk->id_jenis ? 'selected' : '' }}>{{ $jk->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tanggal_booking" class="col-md-4 col-form-label">Tanggal : </label>
                    <div class="col-md-8">
                        <input type="date" value="{{ $history->tanggal }}" name="tanggal" id="tanggal" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="text" class="col-md-4 col-form-label">Jam : </label>
                    <div class="col-md-8">
                        <select class="form-control" name="jam" id="jam" style="width:100%">
                            <option value="" disabled selected hidden>Pilih Jam Pengerjaan</option>
                            @while($jam<=20):
                                <option value='{{ $jam }}' {{ $history->jam==$jam? 'selected':'' }}>{{ $jam }} : 00</option>
                                {{$jam++}}
                            @endwhile
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="harga" class="col-md-4 col-form-label">Harga : </label>
                    <div class="col-md-8">
                        <input type="text" name="harga" value="{{ $history->jenisKendaraan->harga }}" id="harga" class="form-control" disabled>
                        <span id="telepon_error"></span>
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
                            <input type="submit" id="btnAddSave" class="text-light btn btn-primary" value="Ubah Transaksi">
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
        $.get("{{ route('search.name') }}",{
            'search':value
          },
          function(data,status){
            $('#nama_customer').val(data);
            console.log(data);
          });
    });
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

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