@extends('layouts.vendormaster')
@section('title')
BRINGOO | Delivery Boy Manager
@stop
@section('content') 

<script type="text/javascript">

    var init = [];

    function search() {
      

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/vendor/delivery-boy') }}',
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
		
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        var user_search_name = $.trim($('#user_search_name').val());
        var user_search_uniq_id = $.trim($('#user_search_uniq_id').val());   
        var datepicker = $.trim($('#datepicker').val());   
        var datepicker2 = $.trim($('#datepicker2').val());   
        var search_user_type = $.trim($('#search_user_type').val());   
        var search_user_status = $.trim($('#search_user_status').val());   


             
        window.location.href = '{{ URL::to('/vendor/export-delivery-boy') }}'+'?user_search_name='+user_search_name+
        '&user_search_uniq_id='+user_search_uniq_id+
        '&datepicker='+datepicker+
        '&datepicker2='+datepicker2+
        '&search_user_type='+search_user_type+
        '&search_user_status='+search_user_status;  
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}   		
    }

    function add_record() {

        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('Add Delivery Boy');
        $('#UserModal').load('{{ URL::to('/vendor/add-delivery-boy') }}');
        $("#myModal").modal();
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
    }

    function edit_record(edit_id) {

        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('Edit Delivery Boy');
        $('#UserModal').load('{{ URL::to('/vendor/edit-delivery-boy') }}'+'/'+edit_id);
        $("#myModal").modal();
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
    }

    function view_record(view_id) {

        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('View Delivery Boy');
        $('#UserModal').load('{{ URL::to('/vendor/view-delivery-boy') }}'+'/'+view_id);
        $("#myModal").modal();
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
    }

    function loadPiece( href ) {

        $('body').on('click', 'ul.pagination a', function() {
          
            //var getPage = $(this).attr("href")..split('page=')[1];
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
                  //alert(msg);
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
		
		var checklogin1 = checklogin();
		if(checklogin1  == true){
		 $.LoadingOverlay("show");
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
      $.ajax({
            dataType: 'json',
            data: { id:id}, 
            type: "POST",
            url: '{{ URL::to('/vendor/delivery-boy-status') }}',
        }).done(function( data ) 
        {   
          search();
          if(data.class == 'success')
            {   $.LoadingOverlay("hide");
		
		        $(".alert-success").remove();
				showMsg(data.message, "success");
			}
          
          
        });
		
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
        
    }

    $(document).ready(function() {
        loadPiece( '{{ URL::to('/vendor/users') }}');
    })
function remove_record(id) 
    { 
	
		var checklogin1 = checklogin();
		if(checklogin1  == true){
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
			   if(confirm("Are you sure you want to delete this item ?")){
      $.ajax({
            dataType: 'json',
            data: { id:id}, 
            type: "POST",
            url: '{{ URL::to('/vendor/deliveryboy-delete') }}',
        }).done(function( data ) 
        {   
          search();
           if(data.class == 'success')
            {showMsg(data.message, "success");}
          
          
        });
			   }
			   
	   }else{
			location.reload();
				$.LoadingOverlay("hide");
		}  		   
    }
</script>
<section class="content-header">
      <h1> Delivery Boy</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/vendor')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Delivery Boy</li>
      </ol>
    </section>
    <section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/vendor/delivery-boy', 'method' => 'post','name'=>'mySearchForm','files'=>true,'novalidate' => 'novalidate','id' => 'mySearchForm')) !!}
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            {!! Form::text('first_name',null, ['class' => 'form-control','id'=>'user_search_name','placeholder'=>'First Name']) !!}
           </div>
		    <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            {!! Form::text('last_name',null, ['class' => 'form-control','id'=>'user_search_name','placeholder'=>'Last Name']) !!}
           </div>
		    <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            {!! Form::text('email',null, ['class' => 'form-control','id'=>'user_search_email','placeholder'=>'Email']) !!}
           </div>
		    <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            {!! Form::text('mobile',null, ['class' => 'form-control','id'=>'user_search_mobile','placeholder'=>'Mobile']) !!}
           </div>
          <!-- <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            {!! Form::text('uniq_id',null, ['class' => 'form-control','id'=>'user_search_uniq_id','placeholder'=>'Unique Id']) !!}
           </div>-->
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
           <!-- <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('type',$helper->SelectUserTypeBoy(),null, ['class' => 'form-control','required'=>'required','id'=>'search_user_type','onkeypress' => 'error_remove()']) !!}
           </div>-->
           <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('status',$helper->SelectUserStatus(),null, ['class' => 'form-control','required'=>'required','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!}
           </div>
		   <div class="col-xs-12 col-sm-6 col-md-2">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('perpage',$helper->SelectPerPageData(),null, ['class' => 'form-control','id'=>'search_user_perpage','onkeypress' => 'error_remove()']) !!}
           </div>
           <div class="col-xs-12 col-sm-6 col-md-12">
                <label></label>
                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>                                            
                <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Delivery Boy"><span class="btn-label icon fa fa-plus"></span>Add Delivery Boy</a>
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
              <h3 class="box-title">Delivery Boy Data Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('vendor.deliveryboy.search')
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