@extends('layouts.vendormaster')
@section('title')
{{Config::get("Site.title")}} | Past Orders List
@stop
@section('content') 

  <section class="content-header"><h1>{{ __('vendor.past_orders') }}</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> {{ __('vendor.home') }}</a></li>
      <li class="active">{{ __('vendor.past_orders') }}</li>
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
            <div class="col-xs-12 col-sm-4 col-md-3">
            <label></label>
                {!! Form::text('date',null, ['class' => 'form-control', 'placeholder' => trans('vendor.date'), 'id'=>'datepicker' ]) !!}
            </div>
            <div class="col-xs-12 col-sm-6 col-md-12">
              <label></label>
              <button style="margin-top: 20px;" id="searchBtn" class="btn btn-outline-primary pull-left" type="button">{{ __('vendor.search_btn') }}</button>
              <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">{{ __('vendor.reset_btn') }}</button>
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
                @include('vendor.orders.past_order_table')
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
    });
  </script>

  <script type="text/javascript">
    $.ajaxSetup({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    // ===========Search function===================
    function search() {
      event.preventDefault();
      $.ajax({
        type: "GET",
        url: '{{ URL::to('vendor/orders/order-history/past-orders') }}',
        data: $('#orderSearchForm').serialize(),
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) { 
            $('#table-div').html(data);
        },
        error: function (e) {
          toastr[e.responseJSON.toster_class](e.responseJSON.msg);
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

