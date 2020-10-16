@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Tambah Account</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="/account" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
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
                    <form method="post" action="{{ route('account.store') }}" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nama') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-nama">Nama Account</label>
                                    <input type="text" name="nama" id="input-nama" class="form-control form-control-alternative{{ $errors->has('nama') ? ' is-invalid' : '' }}" placeholder="{{ __('Nama') }}" value="{{ old('nama') }}" required autofocus>

                                        @if ($errors->has('nama'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nama') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="form-group{{ $errors->has('pemilik') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-pemilik">Pemilik</label>
                                    <input type="text" name="pemilik" id="input-pemilik" class="form-control form-control-alternative{{ $errors->has('pemilik') ? ' is-invalid' : '' }}" placeholder="{{ __('Pemilik') }}" value="{{ old('pemilik') }}" required>

                                    @if ($errors->has('pemilik'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('pemilik') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nomor_acc') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-nomor-acc">Nomor Account</label>
                                    <input type="text" name="nomor_acc" id="input-nomor-acc" class="form-control form-control-alternative{{ $errors->has('nomor_acc') ? ' is-invalid' : '' }}" placeholder="{{ __('Nomor Account') }}" value="{{ old('nomor_acc') }}"required>
                                        @if ($errors->has('nomor_acc'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nomor_acc') }}</strong>
                                        </span>
                                        @endif
                                </div>
                                <div class="form-group{{ $errors->has('saldo') ? ' has-danger' : '' }}">
                                    <label for="basic-url">Saldo</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="tanda-saldo">Rp</span>
                                        </div>
                                        <input type="text" name="saldo" id="input-saldo" class="form-control form-control-alternative{{ $errors->has('saldo') ? ' is-invalid' : '' }}" placeholder="{{ __('Saldo') }}" value="{{ old('saldo') }}"required>
                                        @if ($errors->has('saldo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('saldo') }}</strong>
                                        </span>
                                        @endif
                                    </div>
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

@endsection

@push('scripts')
    <script src="https://cdn.rawgit.com/igorescobar/jQuery-Mask-Plugin/1ef022ab/dist/jquery.mask.min.js"></script>
    <script type="text/javascript">  
    $(document).ready(function(){
    // Format mata uang.
    $( '#input-saldo' ).mask('0,000,000,000', {reverse: true});
    })
    </script>  
@endpush