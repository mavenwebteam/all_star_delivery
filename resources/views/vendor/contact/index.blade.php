@extends('layouts.vendormaster')
@section('title')
{{Config::get("Site.title")}} | Contact
@stop
@section('content') 

  <section class="content-header"><h1>{{ trans('vendor.menu_contact') }}</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('vendor.home') }}</a></li>
      <li class="active">{{ __('vendor.menu_contact') }}</li>
    </ol>
  </section>


  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <!-- /.box-header -->
          <div class="box-body" id="table-div">
              {!! $data->description !!}
          </div>
          <!-- /.box-body -->
        </div>
        
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>

  <div id="myModal" class="modal fade form-modal" data-keyboard="false"  role="dialog" style="display: none;">
    <div class="modal-dialog modal-lg modal-big">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">
                    <span class='form-title'></span>
                </h4>
                <button type="button" class="close subbtn" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="UserModal">

            </div>
        </div>
    </div>
  </div>

@stop 


