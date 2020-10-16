@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Edit Pegawai</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="/pegawai" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
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
                    <div class="container-fluid mt--7">
                        <div class="row">
                            <div class="col-xl-12 order-xl-1">      
                                <div class="card-body">
                                    <form method="post" action="{{ route('pegawai.update', $pegawai) }}" autocomplete="off">
                                        @csrf
                                        @method('put')
                                        <div class="pl-lg-4">
                                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-nama">Nama</label>
                                                <input type="text" name="nama" id="input-nama" class="form-control form-control-alternative{{ $errors->has('nama') ? ' is-invalid' : '' }}" placeholder="{{ __('Nama') }}" value="{{ old('nama', $pegawai->nama) }}" required autofocus>

                                                @if ($errors->has('nama'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('nama') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('jabatan') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-jabatan">Jabatan</label>
                                                <input type="text" name="jabatan" id="input-jabatan" class="form-control form-control-alternative{{ $errors->has('jabatan') ? ' is-invalid' : '' }}" placeholder="{{ __('Jabatan') }}" value="{{ old('jabatan', $pegawai->jabatan) }}" required>

                                                @if ($errors->has('jabatan'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('jabatan') }}</strong>
                                                    </span>
                                                @endif
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
            </div>
        </div>
    </div>
</div>
@endsection