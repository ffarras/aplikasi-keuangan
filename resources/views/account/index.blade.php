@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Daftar Account</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="/account/create" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
                <i class="flaticon-circle"></i> Tambah Account
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
                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">No.</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Nama Account</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Pemilik</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Nomor Account</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Saldo Account</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;"><i class="icon-grid"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($account as $acc)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $acc->nama }}</td>
                                        <td>{{ $acc->pemilik }}</td>
                                        <td>{{ $acc->nomor_acc }}</td>
                                        <td>{{ number_format($acc->saldo, 0, '', ',') }}</td>
                                        <td class="text-center">
                                        <form action="{{ route('account.destroy', $acc) }}" method="post">
                                        <a href="{{ route('account.edit', $acc->id) }}" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                                            @csrf
                                            @method('delete')

                                            <button type="button" class="btn btn-danger btn-xs" onclick="confirm('Yakin ingin menghapus?') ? this.parentElement.submit() : ''">
                                            <i class="fas fa-trash-alt"></i>
                                            </button>      
                                        </form>                                
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="atlantis/js/core/jquery.3.2.1.min.js"></script>

@endsection

@push('scripts')

<script src="atlantis/js/plugin/datatables/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable({
            columns: 
            [
                {data: 'DT_RowIndex', name: 'id', class: 'text-center', width: '10px', orderable: false, searchable: false},
                {data: 'nama', name:'nama', width: '100px', class: 'text-center'},
                {data: 'pemilik', name:'pemilik', width: '140px', class: 'text-center'},
                {data: 'nomor_acc', name:'nomor_acc', class: 'text-center'},
                {data: 'saldo', name:'saldo', class: 'text-center'},
                {data: 'action', name: 'action', width: '100px', class: 'text-center', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endpush