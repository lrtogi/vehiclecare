@extends('staff.master')

@section('title', 'Staff | Add Nota')

@section('content')

<div class="container-fluid">
    <div class="card mb-4 py-3 border-left-primary">
        <div class="card-body text-dark">
                <h3>{{__('Tambah Nota')}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <h1 class="h3 mb-2 text-gray-800">No. Pembayaran : {{ $pembayaran->id_pembayaran + 1 }}</h1>
        </div>
        <div class="col-md-3">
            <p id="date" class="text-right"></p>
        </div>
    </div>
    <p class="mb-4">Deskripsi Transaksi : </p>
    <p class="mb-4">Pelanggan dengan No. ID {{ $history->id_customer }} <br />Kendaraan : {{ $history->jenisKendaraan->nama_jenis }} <br />Telah memesan untuk tanggal : {{ $history->tanggal }} <br />Pada jam : {{ $history->jam}} </p>

    
        @if($errors->any())
            <div class="alert alert-warning">
                <ul>
                @foreach($errors->all() as $error)
                    <li><p>{{ $error }}</p></li>
                @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-footer">
                        <h3>Input Nota</h3>
                    </div>
                    <form action="{{ route('notas.store', $history->id_history) }}" method="get">
                        <div class="card-body">
                        {{ csrf_field() }}
                        @method('POST')
                            <div class="form-group row">
                                <label for="harga" class="col-md-4 col-form-label">Harga : </label>
                                <div class="col-md-8">
                                    <input type="text" disabled name="harga" id="harga" class="form-control" value="{{ $history->jenisKendaraan->harga }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="uang" class="col-md-4 col-form-label">Uang : </label>
                                <div class="col-md-8">
                                    <input type="text" name="uang" id="uang" class="form-control" value="{{ old('uang') }}" placeholder="Masukkan uang" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="kembalian" class="col-md-4 col-form-label">Kembalian : </label>
                                <div class="col-md-8">
                                    <input type="text" disabled name="kembalian" id="kembalian" class="form-control" value="{{ old('kembalian') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="/staff/notas" class="text-light btn btn-secondary">Nota</a>
                                </div>  
                                <div class="col-md-8">
                                    <ul class="nav justify-content-end">
                                        <input type="submit" id="btnAddSave" class="text-light btn btn-primary" value="Tambah Nota">
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

@section('script')

<script>
    $(document).ready(function(){
        $('#uang').keyup(function(){
            var uang = $(this).val();
            var harga = $('#harga').val();
            var kembalian = uang-harga;
            $kembalian = addCommas(kembalian);
            $('#kembalian').val($kembalian);
        });

        function addCommas(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        var today = new Date();
        document.getElementById("date").innerHTML="Tanggal : "+(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
    });
</script>

@endsection