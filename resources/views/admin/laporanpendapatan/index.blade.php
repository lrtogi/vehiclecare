@extends('staff.master')

@section('title', 'Laundry Care | Laporan Pendapatan')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 py-4 shadow border-left-primary">
                <div class="card-body">
                    <h5 class="text-dark">{{ __('Laporan Pendapatan') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-left-primary shadow py-3">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-m font-weight-bold text-primary text-uppercase mb-1">Annual Earnings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_earnings">Rp. {{ $sum }}</div>
                                </div>
                                <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Accordion -->
                        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h6 class="m-0 font-weight-bold text-primary">Pilih Tanggal</h6>
                        </a>
                        <!-- Card Content - Collapse -->
                        <div class="collapse show" id="collapseCardExample">
                            <div class="card-body">
                                <div class="form-horizontal bucket-form">
                                    <div class="form-group row">
                                        <label for="TanggalAwal" class="col-md-4 col-form-label">Tanggal : </label>
                                        <div class="col-md-8">
                                            <input type="date" name="tanggalAwal" id="tanggalAwal" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="TanggalAkhir" class="col-md-4 col-form-label">Tanggal : </label>
                                        <div class="col-md-8">
                                            <input type="date" name="tanggalAkhir" id="tanggalAkhir" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="button" class="btn btn-danger" id="pilihTanggal">Pilih Tanggal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Pendapatan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Tipe Kendaraan</th>
                        <th>Harga</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Tipe Kendaraan</th>
                        <th>Harga</th>
                    </tr>
                    </tfoot>
                    <tbody class="data_content">
                        
                    </tbody>
                </table>
                {{ csrf_field() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            var _token = $('input[name="_token"]').val();
            var today = new Date();
            document.getElementById("tanggalAwal").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
            document.getElementById("tanggalAkhir").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
            
            readData();

            function readData(){
                var tglAwal = $('#tanggalAwal').val();
                var tglAkhir = $('#tanggalAkhir').val();
                if(tglAwal!='' && tglAkhir!=''){
                    $.ajax({
                    url:"{{ route('tampil.laporan') }}",
                    method:"POST",
                    data:{tglAwal:tglAwal, tglAkhir:tglAkhir, _token:_token},
                    dataType:"json",
                    success:function(data){
                        var output = '';
                        for(var count = 0; count < data.length; count++)
                        {
                        output += '<tr>';
                        output += '<td>' + data[count].tanggal + '</td>';
                        output += '<td>' + data[count].jam + ' : 00</td>';
                        output += '<td>' + data[count].nama_jenis + '</td>';
                        output += '<td>' + data[count].harga + '</td></tr>';
                        }
                        $('.data_content').html(output);
                    }
                    });
                }
                else{
                    alert('Pilih tanggal');
                }
            }
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

            $('#pilihTanggal').on('click',function(){
                readData();
            });
        });

    </script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
@endsection