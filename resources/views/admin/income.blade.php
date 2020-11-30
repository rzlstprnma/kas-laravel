@extends('layouts.admin')
@section('title')
    Pemasukan
@endsection
@section('css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Data Pemasukan</h6>
        <div class="float-right">
            <button id="addBtn" class="btn btn-sm btn-primary ml-3">Tambah Pemasukan</button>
            </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <input type="date" name="from_date" id="from_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="to_date" id="to_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="float-right">
                    <button id="reportBtn" class="mt-4 btn btn-primary">Lihat Laporan</button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="IncomeTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nama Pemasukan</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th><button class="btn btn-info">Print Pdf</button></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Pemasukan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="income_form">
                @csrf
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label>Nama Pemasukan</label>
                    <input type="text" class="form-control" name="income_name">
                </div>
                <div class="form-group">
                    <label>Jumlah Pemasukan</label>
                    <input type="number" class="form-control" name="nominal">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" id="btnSubmit" value="">Simpan</button>
        </div>
        </form>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>

<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>


<script>    

        var table = {};

        loadData();
        function loadData(from_date = '', to_date = ''){
            table = $('#IncomeTable').DataTable({
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
                processing: true,
                serverSide: true,
                dom: 'Blfrtip',
                buttons: [                    
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },             
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },         
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },         
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                ],
                ajax: {
                    url : "{{ route('api.pemasukan') }}",
                    data: {from_date:from_date, to_date:to_date}
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {data: 'income_name', name: 'income_name'},
                    {data: 'nominal', name: 'nominal'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }


        $("#addBtn").click(function(){
            $("#btnSubmit").val("store")
            $("#exampleModal").modal("show")
        });

        $("#reportBtn").click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if(from_date != '' &&  to_date != '')
            {
                $('#IncomeTable').DataTable().destroy();
                loadData(from_date, to_date);
            }
            else
            {
                alert('Both Date is required');
            }
        });

        $('#btnSubmit').on('click', function(e){
            e.preventDefault()
            if($("#btnSubmit").val() == "store"){
                $.ajax({
                    type: 'POST',
                    url: "{{route('pemasukan.store')}}",
                    data: $('form').serialize(),
                    success: function(){
                        table.ajax.reload();
                        $('#income_form').trigger("reset")
                        $("#exampleModal").modal("hide")
                    }
                })
            }else if($("#btnSubmit").val() == "update"){
                var csrf_token = $('meta[name="csrf-token"]').attr('content')
                $.ajax({
                    type: 'POST',
                    url: "{{url('pemasukan')}}" + "/" + $("input[name='id']").val(),
                    data: {
                        'user_id' : $("input[name='user_id']").val(),
                        'income_name' : $("input[name='income_name']").val(),
                        'nominal' : $("input[name='nominal']").val(),
                        '_method' : 'PUT',
                        '_token' : csrf_token
                    },
                    success: function(){
                        table.ajax.reload();
                        $('#income_form').trigger("reset")
                        $("#exampleModal").modal("hide")
                    }
                })
            }
        });

        function editIncome(id){
            $("#exampleModal").modal("show")
            $("#btnSubmit").val("update")
            var csrf_token = $('meta[name="csrf-token"]').attr('content')
            $.get("{{ url('pemasukan') }}" + "/" + id + "/edit", function(data, status){
                $("input[name='user_id']").val(data.user_id)
                $("input[name='id']").val(data.id)
                $("input[name='income_name']").val(data.income_name)
                $("input[name='nominal']").val(data.nominal)
            })
        }

        function destroyIncome(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content')
            $.ajax({
                  url: "{{ url('pemasukan') }}" + "/" + id,
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

</script>
@endsection