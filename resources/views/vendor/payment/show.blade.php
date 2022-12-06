@extends('layouts.adminmaster')
@section('title')
BRINGOO | Orders Manager
@stop
@section('content') 
<script type="text/javascript">

    var init = [];

    function search() {

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/admin/payment') }}',
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
        var search_user_email = $.trim($('#search_user_email').val());
        var datepicker = $.trim($('#datepicker').val());   
        var datepicker2 = $.trim($('#datepicker2').val());        
        window.location.href = '{{ URL::to('/admin/export-payment') }}'+'?search_user_email='+search_user_email+
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
 function view_user_record(view_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('View User');
        $('#UserModal').load('{{ URL::to('/admin/view-user') }}'+'/'+view_id);
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


        loadPiece( '{{ URL::to('/admin/payment') }}');
    })

</script>
<section class="content-header"><h1>Payment</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payment</li>
      </ol>
</section>
<section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/admin/orders', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}

            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('name',null, ['class' => 'form-control','placeholder' => 'User / Vendor name','id'=>'search_user_email' ]) !!}
            </div>
			 <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('order_id',null, ['class' => 'form-control','placeholder' => 'Order Id' ]) !!}
            </div>
			<!--<div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('product_id',$product_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('category_id',$category_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>-->
		    <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('transaction_id',null, ['class' => 'form-control','placeholder' => 'Transaction Id' ]) !!}
            </div>
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('payment_mode',$helper->GetPaymentMode(),null, ['class' => 'form-control','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!}
           </div>
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('payment_status',$helper->GetPaymentStatus(),null, ['class' => 'form-control','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!}
           </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="datepicker" name="start_date" placeholder="Start Date">
              </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker2" name="end_date" placeholder="End Date">
              </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-3">
                <label></label>
                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                <!--<button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>-->
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
              <h3 class="box-title">Payment Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('admin.payment.search')
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