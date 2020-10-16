@extends('_layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="text-center">   
                        {{ __('Reset Password') }}
                    </div>

                </div>

                <div class="text-center text-muted mt-2">
                        <small>
                            Masukkan email untuk mereset password
                        </small>    
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0  justify-center">
                            <div class="col center">
                                <div class="text-center text-muted mt-2">   
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Kirim Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection