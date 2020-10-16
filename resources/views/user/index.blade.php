@extends('_layouts.app')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Daftar Admin</h2>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="/user/create" class="btn btn-white btn-border btn-round" style="border: 2px solid white !important;">
                <i class="la flaticon-add-user"></i> Tambah Admin
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Created at</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user as $user)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td class="text-right">
                                        @if ($user->id != auth()->id())
                                        <form action="{{ route('user.destroy', $user) }}" method="post">
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                                            @csrf
                                            @method('delete')

                                            <button type="button" class="btn btn-danger btn-xs" onclick="confirm('Yakin ingin menghapus?') ? this.parentElement.submit() : ''">
                                            <i class="fas fa-trash-alt"></i>
                                            </button>      
                                        </form>  
                                        @else     
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a> 
                                        @endif                         
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
    <script src="atlantis/js/core/jquery.3.2.1.min.js"></script>
@endsection

@push('scripts')
    <script src="atlantis/js/plugin/datatables/datatables.min.js"></script>

    <script >
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
            });
        });
    </script>
@endpush