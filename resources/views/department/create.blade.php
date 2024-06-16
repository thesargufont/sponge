@extends('layouts.layout')

@section('auth')
<h4 class="pull-left page-title">Tambah Data Bagian</h4>
<ol class="breadcrumb pull-right">
    <li><a href="#">{{Auth::user()->name}}</a></li>
    <li class="active">Tambah Data Bagian</li>
</ol>
<div class="clearfix"></div>
@endsection

@section('content')
<div class="container">
    <div class="card-header">
        <div class="btn-group" role="group">
            <div class="form-group">
                <button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> {{ucwords(__('Simpan'))}}</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form method="POST" id="search-form" class="form" role="form">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Form Data Bagian</h3>
                    </div>
                    <div class="panel-body">
                        {{-- NAMA BAGIAN --}}
                        <div class="row mb-2">
                            <label class="col-md-2">NAMA BAGIAN *</label>
                            <div class="col-md-6">
                                <input required id="department_name" type="text" class="text-uppercase form-control" name="department_name" title="NAMA BAGIAN" placeholder="NAMA BAGIAN">
                            </div>
                        </div>
                        <br>

                        {{-- DESKRIPSI --}}
                        <div class="row mb-2">
                            <label class="col-md-2">DESKRIPSI *</label>
                            <div class="col-md-6">
                                <textarea required class="form-control" rows="5" id="description" type="text" class="text-uppercase form-control" name="description" title="DESKRIPSI" placeholder="DESKRIPSI"></textarea>
                            </div>
                        </div>
                        <br>

                    </div> <!-- panel-body -->
                </div> <!-- panel -->
            </form>
        </div> <!-- col -->
    </div>
</div>

<!-- Plugins js -->
<script src="{{ asset('plugins/timepicker/bootstrap-timepicker.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript') }}"></script>
<script src="{{ asset('plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript') }}"></script>
<script src="{{ asset('pages/form-advanced.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>



@endsection