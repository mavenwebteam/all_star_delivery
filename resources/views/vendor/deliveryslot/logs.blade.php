@extends('layouts.adminmaster')
@section('title')
BRINGOO | Customer Logs
@stop
@section('content') 
<script type="text/javascript">

    var init = [];

    function search() {

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/admin/user-logs') }}',
            data: $('#mySearchForm').serialize(),
            beforeSend: function(){
                $.LoadingOverlay("show");
            },
            success: function(msg){
              
               
                $('#replace-div').html(msg);
                $('.loading-top').fadeOut();
                $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);
                $.LoadingOverlay("hide");
                return false;
            }
        });
    }

    function exportData() {
        var user_search_name = $.trim($('#user_search_name').val());
        var datepicker = $.trim($('#datepicker').val());   
        var datepicker2 = $.trim($('#datepicker2').val());   
       


             
        window.location.href = '{{ URL::to('/admin/export-orders') }}'+'?user_search_name='+user_search_name+
       
        '&datepicker='+datepicker+
        '&datepicker2='+datepicker2;    
    }
    function add_record() {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Add Product');
        $('#UserModal').load('{{ URL::to('/admin/add-product') }}');
        $("#myModal").modal();
    }

    function edit_record(edit_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Edit Product');
        $('#UserModal').load('{{ URL::to('/admin/edit-product') }}'+'/'+edit_id);
        $("#myModal").modal();
    }

    function view_record(view_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('View Order Detail');
        $('#UserModal').load('{{ URL::to('/admin/view-orders') }}'+'/'+view_id);
        $("#myModal").modal();
    }

    function loadPiece( href ) {

        $('body').on('click', 'ul.pagination a', function() {
          var getPage = $(this).attr('href').split('page=')[1];
            //alert(getPage);
            var go_url = href+'?page='+getPage;
            $.ajax({
                type: 'POST',
                url: go_url,
                beforeSend:  function(){
                    $.LoadingOverlay("show");
                },
                data: ($('#mySearchForm').serialize()),
                success: function(msg){
                    $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);
                    $('#replace-div').html(msg);
                    $.LoadingOverlay("hide");
                    return false;
                }
            });
            return false;
        });
    }

    function statusChange(id) 
    {
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
      $.ajax({
            dataType: 'json',
            data: { id:id}, 
            type: "POST",
            url: '{{ URL::to('/admin/product-status') }}',
        }).done(function( data ) 
        {   
          search();
          if(data.class == 'success')
            {showMsg(data.message, "success");}
          
          
        });
        
    }

    function remove_record(id) 
    {
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
      $.ajax({
            dataType: 'json',
            data: { id:id}, 
            type: "POST",
            url: '{{ URL::to('/admin/category-remove') }}',
        }).done(function( data ) 
        {   
          search();
            $("#msg-data").html(data.message);
          
          
        });
        
    }

    $(document).ready(function() {


        loadPiece( '{{ URL::to('/admin/orders') }}');
    })

</script>
<section class="content-header"><h1>Customer Logs</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customer Logs</li>
      </ol>
</section>
<section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/admin/user-logs', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
			
			  {!! Form::hidden('customer_id',$customer_id,['id'=>'id']) !!}
			<div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('product_id',$product_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('category_id',$category_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>
		   
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('brand_id',$brand_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('name',null, ['class' => 'form-control','placeholder' => 'Customer Name' ]) !!}
            </div>
			 <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('order_id',null, ['class' => 'form-control','placeholder' => 'Order Id' ]) !!}
            </div>
			 <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('transaction_id',null, ['class' => 'form-control','placeholder' => 'Transaction Id' ]) !!}
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="datepicker" name="start_date" placeholder="dd/mm/yyyy">
              </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker2" name="end_date" placeholder="dd/mm/yyyy">
              </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('payment_mode',$helper->GetPaymentMode(),null, ['class' => 'form-control','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!}
           </div>
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('status',$helper->GetPaymentStatus(),null, ['class' => 'form-control','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!}
           </div>
		   
		    <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('order_delivery_status',$helper->GetDeliveryStatus(),null, ['class' => 'form-control','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!}
           </div>
             <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('perpage',$helper->SelectPerPageData(),null, ['class' => 'form-control','required'=>'required','id'=>'search_user_perpage','onkeypress' => 'error_remove()']) !!}
           </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <label></label>
                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                                                          
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
            <div class="box-header">
              <h3 class="box-title">Orders Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 <table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>Order Id</th>
   
	 <th>Trancation Id</th>
	 <th>Quantity</th>
    <th>Net Amount</th>
	<th>Delivery Charges</th>
    <th>Total Amount</th>
    <th>Payment Mode</th>
	<th>Is Cancelled</th>
	<th>Delivery Status</th>
    <th>Payment Status</th>
    <th>Created</th>
    <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;?>
      @foreach($orderdata as $data)
    <tr>
      <td>{{$i}}</td>
     
      <td>{{$data->id}}</td>
	 
	  <td>{{$data->transaction_id}}</td>
	  <td>{{$data->quantity}}</td>
      <td>{{$data->total_amount}}</td>
      <td>{{$data->total_shipping_amount}}</td>
      <td>{{$data->net_amount}}</td>
      <td>@if($data->payment_mode == 1) COD @elseif($data->payment_mode == 2) Card @else Online @endif</td>
	  <td>@if($data->is_cancelled == 1) Yes @else No @endif</td>
	   <td>@if($data->order_delivery_status == 0) sent to restaurant @elseif($data->order_delivery_status == 1) accepted by restaurant @elseif($data->order_delivery_status == 2) preparing order @elseif($data->order_delivery_status == 3) picked up and flying to you @elseif($data->order_delivery_status == 4) arrived @else deliverd @endif</td>
      <td>@if($data->status == 1) Complete @elseif($data->status == 2) Pending @else Failed @endif</td>
      <td>{{$data->created_at}}</td>
      <td class="res-dropdown" style="" align="center">
      <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Order Detail" onclick="view_record('{{base64_encode($data->id)}}')" ><i class="fa fa-eye" aria-hidden="true"></i></a></a>
      

    </td>
    </tr>  
    <?php $i++;?>
    @endforeach
    @if($i<2)
    <tr>
    <td>No Orders Data</td>
    </tr>
    @endif    
    </tbody> 
    </table>{!! $orderdata->links() !!}  Records {{ $orderdata->firstItem() }} - {{ $orderdata->lastItem() }} of {{ $orderdata->total() }} (for page {{ $orderdata->currentPage() }} )
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
                    <i class="fa fa-user"></i>&nbsp;&nbsp;<span class='form-title'></span>
                </h4>
                <button type="button" class="close subbtn" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="UserModal">

            </div>
        </div>
    </div>
</div>    </div>
</div>
<script>
  $(function () {
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })
    $('#datepicker2').datepicker({
      autoclose: true
    })
    
  })
</script>
@stop 