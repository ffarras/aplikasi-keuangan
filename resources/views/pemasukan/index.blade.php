@extends('_layouts.app')

@section('styles')
<style>
tfoot {
  background-color: #E8DFDF;
  font-size: 80%;
}
</style>

@endsection

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Daftar Pemasukan Bulan Ini</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="{{ route('pemasukan.create') }}" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
                <i class="flaticon-circle"></i> Tambah Pemasukan
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
                        <div class="row ml-2 mr-2">
                            <div class="form-group form-inline">
                                <h5 class="mt-1">Start Date <span class="text-danger"></span></h5>
                                <div class="controls ml-3">
                                    <input type="text" name="start_date" id="start_date" class="form-control-sm datepicker" autocomplete="off" placeholder="Tanggal awal"><div class="help-block"></div>
                                </div>
                            </div>
                            <div class="form-group form-inline">
                                <h5 class="mt-1">End Date <span class="text-danger"></span></h5>
                                <div class="controls ml-3">
                                    <input type="text" name="end_date" id="end_date" class="form-control-sm datepicker" autocomplete="off" placeholder="Tanggal akhir"><div class="help-block"></div>

                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="text" id="btnFiterSubmitSearch" class="btn btn-info btn-sm">Submit</button>
                            </div>
                            <div class="mt-2 ml-1">
                                <button type="text" id="refresh" class="btn-border btn-info btn-sm">Refresh</button>
                            </div>
                            <div class="mt-2 ml-auto mr-2">
                                <button type="text" id="semua" class="btn-border btn-info btn-sm">Tampilkan Semua Data</button>
                            </div>
                        </div>
                    
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">No.</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Tanggal</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Account</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Aktivitas</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Jumlah</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;"><i class="icon-grid"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                            <tfoot>
                                <tr>
                                <th colspan="3" color="white"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script> 
<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            // pageLength: -1,
            // dom: 'Bfrtip',
            // buttons: [
            // {
            //     extend: 'excelHtml5',
            //     title: 'Data Pemasukan Azura Labs',
            //     message: '  ',
            //     exportOptions: {
            //         columns: [ 0, 1, 2, 3, 4 ]
            //     },
            //     footer: true
                
            // },
            // {
            //     extend: 'copy'
            // },
            // // {
            // //     extend: 'print',
            // //     exportOptions: {
            // //         columns: [ 0, 1, 2, 3, 4 ]
            // //     },
            // //     footer: true
            // // },
            // {
            //     extend: 'pdfHtml5',
            //     title: 'Data Pemasukan Azura Labs',
            //     footer: true,
            //     customize: function (doc) {
            //         doc.content[1].table.widths = 
            //             Array(doc.content[1].table.body[0].length + 1).join('*').split('');
            //     }
            // }
            // ],
            ajax: 
            {
                url: "{{ route('table.pemasukan') }}",
                type: 'GET',
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    }
            }, 
            order: [[ 1, "asc" ]],
            columns: 
            [
                {data: 'DT_RowIndex', name: 'id', class: 'text-center', width: '10px', orderable: false, searchable: false},
                {data: 'tanggal', width: '100px', class: 'text-center'},
                {data: 'account.nama', width: '140px', class: 'text-center'},
                {data: 'aktivitas', class: 'text-center'},
                {data: 'jumlah', class: 'text-center'},
                {data: 'action', name: 'action', width: '100px', class: 'text-center', orderable: false, searchable: false}
            ],
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var renderer = $.fn.dataTable.render.number( ',', '.', 0, 'Rp ' ).display;

            // Update footer
          
          $( api.column( 3 ).footer() ).html(
            'Total'
            );
          $( api.column( 4 ).footer() ).html(
            renderer(pageTotal)
            );
        }
        });

        $('#basic-datatables').on('click', '.btn-delete[data-remote]', function (e) { 
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(this).data('remote');
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

        $('#btnFiterSubmitSearch').click(function(){
            $('#basic-datatables').DataTable().draw(true);
        });

        $('#refresh').click(function(){
            $('#start_date').val('');
            $('#end_date').val('');
            $('#basic-datatables').DataTable().draw(true);
        });

        $('#semua').click(function(){
            $('#start_date').val('01-01-2020');
            $('#end_date').val('31-12-2020');
            $('#basic-datatables').DataTable().draw(true);
        });

        $('.datepicker').datepicker({  
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });  
    });
</script>

@endpush

