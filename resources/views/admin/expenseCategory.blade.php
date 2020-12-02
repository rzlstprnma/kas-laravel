@extends('layouts.admin')
@section('title')
    Kategori Pengeluaran
@endsection
@section('css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kategori Pengeluaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoryTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="categoryForm" action="#" method="post">
                    @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label for="category_name">Nama Kategori</label>
                        <input type="text" class="form-control" name="category_name" id="category_name">
                    </div>
                    <button type="submit" id="btnSubmit" value="save" class="float-right btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>
<script>
        var table = 
        $('#categoryTable').DataTable({
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.kategori') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex' },
                {data: 'category_name', name: 'category_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#categoryForm').on('submit', function(e){
            e.preventDefault();
            if($("#btnSubmit").val() == "save"){
                $.ajax({
                    type: 'POST',
                    url: "{{route('kategori-pengeluaran.store')}}",
                    data: $('form').serialize(),
                    success: function(res){
                        if(res.errors){
                            $('.alert-danger').html('');
                            $.each(res.errors, function(key, value) {
                                $('.alert-danger').show();
                                $('.alert-danger').append('<strong><li>'+value+'</li></strong>');
                            });
                        }else{
                            table.ajax.reload();
                            $('#categoryForm').trigger("reset")
                        }
                    }
                })
            }else if($("#btnSubmit").val() == "edit"){
                var csrf_token = $('meta[name="csrf-token"]').attr('content')
                $.ajax({
                    type: 'POST',
                    url: "{{url('kategori-pengeluaran')}}" + "/" + $("input[name='id']").val(),
                    data: {
                        'user_id' : $("input[name='user_id']").val(),
                        'category_name' : $("input[name='category_name']").val(),
                        '_method' : 'PUT',
                        '_token' : csrf_token
                    },
                    success: function(res){
                        if(res.errors){
                            $('.alert-danger').html('');
                            $.each(res.errors, function(key, value) {
                                $('.alert-danger').show();
                                $('.alert-danger').append('<strong><li>'+value+'</li></strong>');
                            });
                        }else{
                            table.ajax.reload();
                            $('#categoryForm').trigger("reset")
                        }
                    }
                })
            }
        });

        function destroyCategory(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content')
            $.ajax({
                  url: "{{ url('kategori-pengeluaran') }}" + "/" + id,
                  type: "POST",
                  data: {
                    '_method' : 'DELETE', 
                    '_token' : csrf_token
                  },
                  success: function(data){
                    table.ajax.reload();         
                  },
            });
        }

        function editCategory(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content')
            $.get("{{ url('kategori-pengeluaran') }}" + "/" + id + "/edit", function(data, status){
                $("input[name='category_name']").val(data.category_name)
                $("input[name='user_id']").val(data.user_id)
                $("input[name='id']").val(data.id)
                $("#btnSubmit").val("edit")
            })
        }
</script>
@endsection