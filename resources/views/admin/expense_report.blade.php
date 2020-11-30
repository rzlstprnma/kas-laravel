@extends('layouts.admin')
@section('title')
    Pengeluran
@endsection
@section('content')
    <!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Data Pengeluaran</h6>
    </div>
    <div class="card-body">
        <form action="#">
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="float-right">
                        <button class="mt-4 btn btn-primary">Lihat Laporan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>    
@endsection