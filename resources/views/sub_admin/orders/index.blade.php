@extends('layouts.sub_admin_master')
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
            <div class="col-xs-12 col-sm-6 col-md-3">
              <label></label>
              <input type="text" class="form-control" id="datepicker" name="start_date" autocomplete="off" placeholder="Star Date">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
              <label></label>
              <input type="text" class="form-control" id="datepicker2" name="end_date" autocomplete="off" placeholder="End Date" id="end_date">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
              <div class="form-group">
                <label></label>
                <select name="store_id" id="storeListSelect2" class="form-control select2" autocomplete="off" data-placeholder="Search a store" style="width: 100%; border-radius: 0px;" >
                  <option value="" disabled  selected>Select Store</option>
                </select>
              </div>
           </div>



            <div class="col-xs-12 col-sm-6 col-md-3">
              <label></label>
              <select name="status" class="form-control">
                <option value="">-Choose Status-</option>
                <option value="CANCELLED">Cancelled</option>
                <option value="DELIVERED">Delivered</option>
                <option value="RUNNING">Running</option>
              </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-12">
              <label></label>
              <button style="margin-top: 20px;" id="searchBtn" class="btn btn-outline-primary pull-left" type="button">{{ trans('vendor.search_btn') }}</button>
              <button style="margin-top: 20px;margin-left: 10px;" id="resetBtn" class="btn btn-outline-success pull-left" type="button">{{ trans('vendor.reset_btn') }}</button>
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
                @include('sub_admin.orders.table')
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

 <!-- Select2 -->
 <script src="{{ asset('admin_assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
  $('.select2').select2();
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
      if(confirm(`Are you sure want to ${status}`))
      {
        $.ajax({
          dataType: 'json',
          data: { id:id, status}, 
          type: "PUT",
          url: '{{ URL::to('/sub-admin/orders/update/status') }}'+'/'+id,
          success: function (data) { 
            search();
            toastr[data.toster_class](data.msg);
          },
          error: function (e) {
            toastr[e.responseJSON.toster_class](e.responseJSON.msg);
          }
        })
      }
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
        url: '{{ URL::to('sub-admin/orders') }}',
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
      let startDate = $('#datepicker').val();
      let endDate = $('#datepicker2').val();
      if(startDate && endDate) {
        startDate = new Date(startDate);
        endDate = new Date(endDate);
        if(endDate < startDate){
          toastr['error']('Start date must be grater then end date!');
        }else{
          search();
        }
      }else{
        search();
      }
    });
    // ========reset =================
    $(document).on('click','#resetBtn', function(event){
      $('#orderSearchForm').trigger('reset');
      $("#storeListSelect2").empty();
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
      const modalTitle = "Order Details";
      const orderId = modelAction(thisObj, modalTitle);
      $('#modelBody').load('{{ URL::to('sub-admin/orders') }}'+'/'+orderId);
      $("#modal-lg").modal(); 
      $.LoadingOverlay("hide");
    });

    //---------show edit order modal-----
    $(document).on('click','.editOrderBtn', function(){
      const thisObj = $(this);
      const modalTitle = "Edit Order";
      const orderId = modelAction(thisObj, modalTitle);
      $('#modelBody').load('{{ URL::to('sub-admin/orders') }}'+'/'+orderId+'/edit');
      $("#modal-lg").modal(); 
      $.LoadingOverlay("hide");
    });

    // --------select2 storeList start-------
    $("#storeListSelect2").attr(
      "data-placeholder","Please select an skill"
    );
    $('#storeListSelect2').select2({
      minimumInputLength: 2,
      ajax: {
            url: '{{ route("subAdmin.select2.stores") }}',
            dataType: 'json',
            delay: 250,
            pagination: {
                  more: true
                },
            data: function (params) {
                //console.log(params);
                var query = {
                search: params.term || '',
                page: params.page || 1
                }
                // Query parameters will be ?search=[term]&page=[page]
                return query;
            },
            processResults: function (data) {
              //console.log(data);
              return {
                  'results': data.results
                , 'pagination': {
                      'more': data.pagination.more
                  }
              };
          }
      }
    });
    // --------select2 storeList end-------
  </script>
@endpush


@push('style')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin_assets/bower_components/select2/dist/css/select2.min.css') }}">
@endpush 

