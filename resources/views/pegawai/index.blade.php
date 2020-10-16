@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Daftar Pegawai</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="/pegawai/create" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
                <i class="la flaticon-add-user"></i> Tambah Pegawai
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
                                <th scope="col">No.</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col"><i class="icon-grid"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.pegawai') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'id', class: 'text-center', width: '50px'},
                {data: 'nama', name:'nama', class: 'text-center'},
                {data: 'jabatan', name:'jabatan', class: 'text-center'},
                {data: 'action', name: 'action', width: '80px', class: 'text-center'}
            ]
        });

        $('#basic-datatables').on('click', '.btn-delete[data-remote]', function (e) { 
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(this).data('remote');
            // confirm then
            if (confirm('Yakin ingin menghapus?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: 'DELETE', submit: true}
                }).always(function (data) {
                    swal({
                    title: 'Data berhasil dihapus',
                    type: 'success',
                    icon: 'success'
                    });
                    $('#basic-datatables').DataTable().draw(false);
                });
            }
        });
    });
</script>

@endpush