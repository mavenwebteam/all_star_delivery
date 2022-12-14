@extends('layouts.vendormaster')
@section('title')
{{Config::get("Site.title")}} | Orders List
@stop
@section('content') 

  <section class="content-header"><h1>{{ trans('vendor.orders') }}</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('vendor.home') }}</a></li>
      <li class="active">{{ __('vendor.orders') }}</li>
    </ol>
  </section>
  <section class="" style="padding: 15px;">
    <div class="row">
      <div class="col-xs-12 page-user">
        <div class="box">
          <div class="box-body">
          {!! Form::open(array('route' => 'vendor.menu-manager.index', 'method' => 'get','name'=>'mySearchForm','files'=>true,'id' => 'orderSearchForm')) !!}
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('order_id',null, ['class' => 'form-control','placeholder' => trans('vendor.order_id') ]) !!}
            </div>
            <div class="col-xs-12 col-sm-6 col-md-12">
              <label></label>
              <button style="margin-top: 20px;" id="searchBtn" class="btn btn-outline-primary pull-left" type="button">{{ trans('vendor.search_btn') }}</button>
              <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">{{ trans('vendor.reset_btn') }}</button>
            </div>
          {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </section>     

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <!-- /.box-header -->
          <div class="box-body" id="table-div">
                @include('vendor.orders.search')
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
                <button type="button" class="close subbtn" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body" id="UserModal">

            </div>
        </div>
    </div>
  </div>
  {{-- modal include --}}
  @include('includes.modal')
@stop 

@push('script')
  <script>
    $(function () {
      //Date picker
      $('#datepicker').datepicker({
        autoclose: true
      })
      $('#datepicker2').datepicker({
        autoclose: true
      })
      
    });
  </script>

  <script type="text/javascript">
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });

    /**
     * Order accept
     * @argument int order Id
     * 
    */
    function orderStatusUpdate(id, status) 
    {
      $.ajax({
        dataType: 'json',
        data: { id:id, status}, 
        type: "PUT",
        url: '{{ URL::to('/vendor/orders/status/update') }}'+'/'+id,
        success: function (data) { 
          search();
          toastr[data.toster_class](data.msg);
        },
        error: function (e) {
          toastr.error(e.responseJSON.msg);
        }
      })
    };
    $(document).on('click', '.orderStatusBtn', function(event){
      event.preventDefault();
      const orderId = $(this).attr('orderId');
      const status = $(this).attr('state');
      orderStatusUpdate(orderId, status);
    });
    // -------order accept end------------

     // ===========Search function===================
    function search() {
      event.preventDefault();
      $.ajax({
        type: "GET",
        url: '{{ URL::to('vendor/orders') }}',
        data: $('#orderSearchForm').serialize(),
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) { 
            $('#table-div').html(data);
        },
        error: function (e) {
            toastr[data.toster_class](data.msg);
        }
      });
    }
    //========== Search form submit start ============
    $(document).on('click','#searchBtn', function(event){
        search();
    });
    
    //-------------model comon action--------
    function modelAction(thisObj, modalTitle) {
      const orderId = thisObj.attr('orderId');
      $.LoadingOverlay("show");
      $('#modelBody').html(''); 
      $(".modal-title").text(modalTitle);
      return orderId;
    }
    //---------show order detail modal-----
    $(document).on('click','.viewOrderBtn', function(){
      const thisObj = $(this);
      const modalTitle = " {{ __('vendor.order_details') }} ";
      const orderId = modelAction(thisObj, modalTitle);
      $('#modelBody').load('{{ URL::to('vendor/orders') }}'+'/'+orderId);
      $("#modal-lg").modal(); 
      $.LoadingOverlay("hide");
    });

    function resetSearchForm(){
      $("#orderSearchForm").trigger("reset");
      search();
    }

  </script>
@endpush

