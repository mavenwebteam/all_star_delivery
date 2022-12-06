@extends('layouts.sub_admin_master')
@section('title')
BRINGOO | Delivery Boy Manager
@stop
@section('content') 
<script type="text/javascript">

    var init = [];

    function search() {

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/sub-admin/assign_delivery_boy') }}',
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
       


             
        window.location.href = '{{ URL::to('/sub-admin/export-orders') }}'+'?user_search_name='+user_search_name+
       
        '&datepicker='+datepicker+
        '&datepicker2='+datepicker2;    
    }
    function add_record() {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Add Product');
        $('#UserModal').load('{{ URL::to('/sub-admin/add-product') }}');
        $("#myModal").modal();
    }

    function edit_record(edit_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Edit Product');
        $('#UserModal').load('{{ URL::to('/sub-admin/edit-product') }}'+'/'+edit_id);
        $("#myModal").modal();
    }

    function view_record(view_id) {

        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('View Order Detail');
        $('#UserModal').load('{{ URL::to('/sub-admin/view-orders') }}'+'/'+view_id);
        $("#myModal").modal();
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		} 
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
            url: '{{ URL::to('/sub-admin/category-remove') }}',
        }).done(function( data ) 
        {   
          search();
            $("#msg-data").html(data.message);
          
          
        });
        
    }

    $(document).ready(function() {


        loadPiece( '{{ URL::to('/sub-admin/assign_delivery_boy') }}');
    })

</script>
<section class="content-header"><h1>Assign Delivery Boy</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/sub-admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assign Delivery Boy</li>
      </ol>
</section>
<section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/sub-admin/assign_delivery_boy', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
			<div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php //$helper=new App\Helpers;?>
            {!! Form::select('product_id',$product_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()']) !!}
           </div>
		   

             <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('first_name',null, ['class' => 'form-control','placeholder' => 'First Name' ]) !!}
            </div>
			 <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('last_name',null, ['class' => 'form-control','placeholder' => 'Last Name' ]) !!}
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
                    <input type="text" class="form-control pull-right" id="datepicker" name="start_date" placeholder="Start Date" readonly>
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
              <h3 class="box-title">Assign Delivery Boy Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('sub_admin.orders.searchdeliverboy')
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
  
   //$('.dboy').change(function(){ 
  

</script>
@stop 