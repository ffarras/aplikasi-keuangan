@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Daftar Kategori</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;" id="createKategori">
                <i class="flaticon-circle"></i> Tambah Kategori
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
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">No</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;">Kategori</th>
                                    <th scope="col" style="text-align: center; vertical-align: middle;"><i class="icon-grid"></i></th>
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
</div>

   
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="form-kategori" name="form-kategori" class="form-horizontal">
                    <input type="hidden" name="kategori_id" id="kategori_id">
                    <div class="form-group{{ $errors->has('kategori') ? ' has-danger' : '' }}">
                        <label for="kategori" class="col-sm-2 control-label">Kategori</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-alternative{{ $errors->has('kategori') ? ' is-invalid' : '' }}" id="kategori" name="kategori" placeholder="Kategori" value="" maxlength="50" required>
                            @if ($errors->has('kategori'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('kategori') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
      
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-border mt-4" id="cancel" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary mt-4" id="saveBtn" value="create">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
</body>
@endsection

@push('scripts')
<script src="atlantis/js/plugin/parsleyjs/dist/parsley.min.js"></script>
<script src="atlantis/js/plugin/datatables/datatables.min.js"></script>
<script src="atlantis/js/plugin/sweetalert/sweetalert.min.js"></script>

<script type="text/javascript">
  $(function () {
     
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    
    var table = $('#basic-datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('kategori.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'id', class: 'text-center', width: '40px'},
            {data: 'kategori', name: 'kategori', class: 'text-center'},
            {data: 'action', name: 'action', class: 'text-center', orderable: false, searchable: false, width: '100px'},
        ]
    });
     
    $('#createKategori').click(function () {
        $('#modal-header').css('background-color', '#177dff');
        $('#modal-header').css('color', '#fff');
        $('#saveBtn').val("create-product");
        $('#saveBtn').attr('class', 'btn btn-primary mt-4');
        $('#cancel').attr('class', 'btn btn-border btn-primary mt-4');
        $('#kategori_id').val('');
        $('#form-kategori').trigger("reset");
        $('#modelHeading').html("Tambah Kategori");
        $('#ajaxModel').modal('show');
    });
    
    $('body').on('click', '.editKategori', function () {
        var kategori_id = $(this).data('id');
        $.get("{{ route('kategori.index') }}" +'/' + kategori_id +'/edit', function (data) {
            $(this).html('Simpan');
            $('#modal-header').css('background-color', '#FFAD46');
            $('#modal-header').css('color', '#fff');
            $('#modelHeading').html("Edit Kategori");
            $('#saveBtn').val("edit-user");
            $('#saveBtn').attr('class', 'btn btn-warning mt-4');
            $('#cancel').attr('class', 'btn btn-border btn-warning mt-4');
            $('#ajaxModel').modal('show');
            $('#kategori_id').val(data.id);
            $('#kategori').val(data.kategori);
        })
   });
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Menyimpan...');
    
        $.ajax({
          data: $('#form-kategori').serialize(),
          url: "{{ route('kategori.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            swal({
                title: 'Data berhasil disimpan',
                type: 'success',
                icon: 'success'
                });
            $('#form-kategori').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Simpan');
          }
      });
      $(this).html('Simpan');
    });
    
    $('body').on('click', '.deleteKategori', function () {
     
        var kategori_id = $(this).data("id");
        
        if(confirm("Yakin ingin menghapus?")){
            $.ajax({
                type: "DELETE",
                url: "{{ route('kategori.store') }}"+'/'+kategori_id,
                success: function (data) {
                    swal({
                        title: 'Data berhasil dihapus',
                        type: 'success',
                        icon: 'success'
                    });
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
     
  });
</script>
@endpush