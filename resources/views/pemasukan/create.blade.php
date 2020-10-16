@extends('_layouts.app')

@section('content')

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Tambah Pemasukan</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="{{ route('pemasukan.index') }}" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
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
                    <form method="post" action="{{ route('pemasukan.store') }}" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('admin') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-admin">Admin</label>
                                    <select name="admin" id="input-admin" class="form-control" required>
                                        <option value="{{ auth()->user()->id }}"> {{ auth()->user()->name }} </option>
                                    </select>

                                        @if ($errors->has('admin'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('admin') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="form-group{{ $errors->has('tanggal') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-tanggal">Tanggal</label>
                                    <input type="text" name="tanggal" id="input-tanggal" class="form-control form-control-alternative{{ $errors->has('tanggal') ? ' is-invalid' : '' }}" placeholder="{{ __('Tanggal') }}" value="{{ date('d-m-Y') }}" required autofocus>

                                        @if ($errors->has('tanggal'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tanggal') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="form-group{{ $errors->has('account') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account">Account</label>
                                    <select name="account" id="input-account" class="form-control" required>
                                        <option value="" selected disabled> Pilih account </option>
                                            @foreach ($account as $acc)
                                                @if ( old('account') == $acc->id )
                                                    <option value="{{ $acc->id }}" selected>{{ $acc->nama }}</option>
                                                @else
                                                    <option value="{{ $acc->id }}">{{ $acc->nama }}</option>
                                                @endif
                                            @endforeach
                                    </select>

                                    @if ($errors->has('account'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('account') }}</strong>
                                        </span>
                                    @endif
                                </div>                              
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('aktivitas') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-aktivitas">Aktivitas</label>
                                    <input type="text" name="aktivitas" id="input-aktivitas" class="form-control form-control-alternative{{ $errors->has('aktivitas') ? ' is-invalid' : '' }}" placeholder="{{ __('Aktivitas') }}" value="{{ old('aktivitas') }}" required>

                                    @if ($errors->has('aktivitas'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('aktivitas') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('jumlah') ? ' has-danger' : '' }}">
                                    <label for="basic-url">Jumlah</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="tanda-jumlah">Rp</span>
                                        </div>
                                        <input type="text" name="jumlah" id="input-jumlah" class="form-control form-control-alternative{{ $errors->has('jumlah') ? ' is-invalid' : '' }}" placeholder="{{ __('Jumlah') }}" value="{{ old('jumlah') }}"required>
                                            @if ($errors->has('jumlah'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('jumlah') }}</strong>
                                            </span>
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('bukti') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-bukti">Bukti (format: png, jpg, jpeg. maks 2MB)</label>
                                    <input type="file" name="bukti[]" id="input-bukti" multiple>
                                        @if ($errors->has('bukti'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('bukti') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('catatan') ? ' has-danger' : '' }}">
                                    <div class="mb-1">Catatan</div>
                                    <input type="text" name="catatan" id="input-catatan" class="form-control form-control-alternative{{ $errors->has('catatan') ? ' is-invalid' : '' }}" placeholder="{{ __('Catatan') }}" value="{{ old('catatan') }}">

                                    @if ($errors->has('catatan'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('catatan') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

@endsection

@push('scripts')
    <script src="https://cdn.rawgit.com/igorescobar/jQuery-Mask-Plugin/1ef022ab/dist/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>  
    <script type="text/javascript">  
    $(document).ready(function(){
        // Format mata uang.
        $( '#input-jumlah' ).mask('0,000,000,000', {reverse: true});

        $('#input-tanggal').datepicker({  
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true,
            todayHighlight: true
            });  
        }) 
    </script>  
@endpush