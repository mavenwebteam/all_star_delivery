@extends('layouts.vendormaster')
@section('title')
BRINGOO | Earning Manager
@stop
@section('content') 
<script type="text/javascript">

    var init = [];

    function search() {

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/vendor/earning-manager') }}',
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
       


             
        window.location.href = '{{ URL::to('/vendor/export-earning') }}'+'?user_search_name='+user_search_name+
       
        '&datepicker='+datepicker+
        '&datepicker2='+datepicker2;    
    }
   
   function view_user_record(view_id) {

        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('View User');
        $('#UserModal').load('{{ URL::to('/vendor/view-user') }}'+'/'+view_id);
        $("#myModal").modal();
		 $.LoadingOverlay("hide");
		 }else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
    }
    function view_record(view_id) { 

        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('View Earning Detail');
        $('#UserModal').load('{{ URL::to('/vendor/view-earning-manager') }}'+'/'+view_id);
		$.LoadingOverlay("hide");
        $("#myModal").modal();
		 }else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
    }
	
	 


    

   

    $(document).ready(function(){


        loadPiece( '{{ URL::to('/vendor/earning-manager') }}');
    })

</script>
<section class="content-header"><h1>Earning</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/vendor')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Earning</li>
      </ol>
</section>
<section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/vendor/earning-manager', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
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
		   
		  <!-- <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('brand_id',$brand_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>-->

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
                    <input type="text" class="form-control pull-right" id="datepicker" name="start_date" placeholder="Satrt Date" readonly>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker2" name="end_date" placeholder="End Date" readonly>
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
            {!! Form::select('perpage',$helper->SelectPerPageData(),null, ['class' => 'form-control','id'=>'search_user_perpage','onkeypress' => 'error_remove()']) !!}
           </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <label></label>
                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>                                            
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
              <h3 class="box-title">Earning Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('vendor.earning.search')
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