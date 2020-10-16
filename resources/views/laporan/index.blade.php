@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Laporan Keuangan</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row mt--2">
        <div class="col-md-12">
            <div class="card full-height">
                <div class="card-body">
                    <form action="{{ route('laporan') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date" class="form-control-label">Start Date</label>
                                    <input type="text" name="start_date" id="start_date" class="form-control form-control-sm datepicker" value="{{ request()->get('start_date') }}" autocomplete="off" placeholder="Tanggal awal" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                              
                                    <label for="end_date" class="form-control-label">End Date</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control form-control-sm datepicker" value="{{ request()->get('end_date') }}" autocomplete="off" placeholder="Tanggal akhir" required>    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ml-auto mr-2">
                                    <label for="jenis_transaksi" class="form-control-label">Transaksi</label>
                                    <select name="jenis_transaksi" id="jenis_transaksi" class="form-control form-control-sm mr-2" required>
                                        <option value="" selected disabled>Pilih</option>
                                        <option value="pemasukan" @if (request()->get('jenis_transaksi') == "pemasukan") selected @endif>Pemasukan</option>
                                        <option value="pengeluaran" @if (request()->get('jenis_transaksi') == "pengeluaran") selected @endif>Pengeluaran</option>
                                        <option value="semua" @if (request()->get('jenis_transaksi') == "semua") selected @endif>Semua Transaksi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mr-2">
                            <button type="submit" id="btnSubmit" class="btn btn-info btn-sm ml-auto mr-2">Submit</button>
                        </div>
                    </form>
                    <!-- <div class="row ml-2 mr-2 mb-3">
                        <div class="mt-2 ml-auto mr-2">
                            <button type="text" id="refresh" class="btn-border btn-info btn-sm">Refresh</button>
                            <button type="text" id="semua" class="btn-border btn-info btn-sm">Tampilkan Semua Data</button>
                        </div>
                    </div> -->
                    @if (request()->jenis_transaksi)
                    @if ($union && $total)
                    <a href="{{ route('laporanpdf', 
                    ['startdate'=>request()->start_date, 'enddate'=>request()->end_date, 'jenis'=>request()->jenis_transaksi]) }}" 
                    target="_blank" class="btn btn-info btn-sm ml-2"><i class="fa fa-print"> Print PDF</i></a>
                    @endif
                    <div class="table-responsive mt-3">
                        <table id="basic-datatables" class="display table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">No.</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Jenis</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Tanggal</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Account</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Aktivitas</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Jumlah</th>
                                </tr>
                            </thead>
                            @if ($union && $total)
                            <tbody>
                                @forelse ($union as $uni)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{$uni->jenis}}</td>
                                        <td>{{$uni->tanggal}}</td>
                                        <td>{{$uni->account->nama}}</td>
                                        <td>{{$uni->aktivitas}}</td>
                                        <td>{{$uni->jumlah}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                <th colspan="4"></th>
                                <th>Total</th>
                                <th>{{number_format($total, 0, '', ',')}}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script src="atlantis/js/plugin/datatables/datatables.min.js"></script>


<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({  
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto"
        });
        $('#basic-datatables').DataTable({
            });
    });
</script>
@endpush