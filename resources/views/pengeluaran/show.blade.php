@extends('_layouts.app')

@section('styles')
<style>
.table-borderless > tbody > tr > td,
.table-borderless > tbody > tr > th,
.table-borderless > tfoot > tr > td,
.table-borderless > tfoot > tr > th,
.table-borderless > thead > tr > td,
.table-borderless > thead > tr > th {
    border: none;
}
</style>
@endsection

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Detail Pengeluaran</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="/pengeluaran" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
                Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row mt--2">
        <div class="col-md-12">
            <div class="card full-height">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                    <th width="40px">Admin</th>
                                    <td>: {{ $pengeluaran->user->name}}</td>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                    <th width="40px">Tanggal</th>
                                    <td>: {{ Carbon\Carbon::parse($pengeluaran->tanggal)->format('d-m-Y') }}</td>
                                    </tr>
                                </thead> 
                                <thead>
                                    <tr>
                                    <th width="40px">Account</th>
                                    <td>: {{ $pengeluaran->account->nama }}</td>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                    <th width="40px">Kategori</th>
                                    <td>: {{ $pengeluaran->kategori->kategori }}</td>
                                    </tr>
                                </thead> 
                                <thead>
                                    <tr>
                                    <th width="40px">Aktivitas</th>
                                    <td>: {{ $pengeluaran->aktivitas }}</td>
                                    </tr>
                                </thead> 
                                <thead>
                                    <tr>
                                    <th width="40px">Jumlah</th>
                                    <td>: Rp {{ $pengeluaran->jumlah }}</td>
                                    </tr>
                                </thead>    
                                <thead>
                                    <tr>
                                        <th width="40px">Catatan</th>
                                        <td>: {{ $pengeluaran->catatan }}</td>
                                    </tr>
                                </thead>                              
                            </table>    
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th width="40px">Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                        @if ($pengeluaran->bukti)
                                        @foreach (json_decode($pengeluaran->bukti) as $foto)
                                            <button type="button" class="btn" data-toggle="modal" data-target="#exampleModal">
                                            <img class="img" src="/uploads/pengeluaran/{{ $foto }}" alt="bukti" width="250px">
                                            </button>
                                        @endforeach
                                        @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn btn-warning">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close py-2" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        <img id="img01" alt="bukti" style="width:100%">
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
    var modal = document.getElementById('exampleModal');

    var img = $('.img');
    var modalImg = $("#img01");
    $('.img').click(function(){
    modal.style.display = "block";
    var newSrc = this.src;
    modalImg.attr('src', newSrc);
    }); 
});
</script>
@endpush