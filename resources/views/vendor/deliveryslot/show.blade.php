@extends('layouts.vendormaster')
@section('title')
BRINGOO | Delivery Slot Manager
@stop
@section('content') 
<?php //echo 'asd'; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdfm7EwZaKgEM6m9fFeXIfPRtcM02JA4k&libraries=places&language=en" type="text/javascript"></script>
<style>

.pac-container {
    background-color: #fff;
    position: absolute!important;
    z-index: 10000;
    border-radius: 2px;
    border-top: 1px solid #d9d9d9;
    font-family: Arial,sans-serif;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    overflow: hidden;
}
</style> 
<script type="text/javascript">

    var init = [];

    function search() {
      

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/vendor/delivery-slot') }}',
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
        var user_search_uniq_id = $.trim($('#user_search_uniq_id').val());   
        var datepicker = $.trim($('#datepicker').val());   
        var datepicker2 = $.trim($('#datepicker2').val());   
        var search_user_type = $.trim($('#search_user_type').val());   
        var search_user_status = $.trim($('#search_user_status').val());   


             
        window.location.href = '{{ URL::to('/vendor/export-picker') }}'+'?user_search_name='+user_search_name+
        '&user_search_uniq_id='+user_search_uniq_id+
        '&datepicker='+datepicker+
        '&datepicker2='+datepicker2+
        '&search_user_type='+search_user_type+
        '&search_user_status='+search_user_status;    
    }

    function add_record() {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Add Delivery Slot');
        $('#UserModal').load('{{ URL::to('/vendor/add-delivery-slot') }}');
        $("#myModal").modal();
    }

    function edit_record(edit_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Edit Delivery Slot');
        $('#UserModal').load('{{ URL::to('/vendor/edit-delivery-slot') }}'+'/'+edit_id);
        $("#myModal").modal();
    }

    function view_record(view_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('View Delivery Slot');
        $('#UserModal').load('{{ URL::to('/vendor/view-delivery-slot') }}'+'/'+view_id);
        $("#myModal").modal();
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
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
      $.ajax({
            dataType: 'json',
            data: { id:id}, 
            type: "POST",
            url: '{{ URL::to('/vendor/delivery-slot-status') }}',
        }).done(function( data ) 
        {   
          search();
          if(data.class == 'success')
            {showMsg(data.message, "success");}
          
          
        });
        
    }

    $(document).ready(function() {
        loadPiece( '{{ URL::to('/vendor/delivery-slot') }}');
    })
$(document).ready(function () { 
    		$( "#store_id" ).change(function () {
    			var store_id = $(this).val();
    				$.ajax({
    					url: "{{url('admin/getoutlet') }}" + '/'+store_id,
    					success: function(data) {
    						$('#outlet_id').prop('disabled', false);
    						$('#outlet_id').html('');
    						$('#outlet_id').html(data);		
    					}
    				});
    		});
        
    	});

</script>
<section class="content-header">
      <h1>Delivery Slot</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/vendor')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Delivery Slot</li>
      </ol>
    </section>
    <section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/vendor/search-delivery-slot', 'method' => 'post','name'=>'mySearchForm','files'=>true,'novalidate' => 'novalidate','id' => 'mySearchForm')) !!}
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
				<?php $helper = new App\Helpers; ?>
            {!! Form::select('outlet_id',$outlet_box,null, ['class' => 'form-control','required'=>'required','id'=>'outlet_id','onChange' => 'error_remove()' ]) !!}
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
            <!--<div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            <?php $helper=new App\Helpers;?>
            {!! Form::select('type',$helper->SelectUserType(),null, ['class' => 'form-control','required'=>'required','id'=>'search_user_type','onkeypress' => 'error_remove()']) !!}
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
                                                          
                <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Delivery Slot"><span class="btn-label icon fa fa-plus"></span>Add Delivery Slot</a>
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
              <h3 class="box-title">Delivery slot Data Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('vendor.deliveryslot.search')
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
<script type="text/javascript">
	google.maps.event.addDomListener(window, 'click', function () {
		var addressBox = document.getElementsByClassName('address');
		
		
		for(var i=0; i< addressBox.length; i++){
			var places = new google.maps.places.Autocomplete(addressBox[i]);
			google.maps.event.addListener(places, 'place_changed', function () {
				var place = places.getPlace();
				console.log(place);
				var address = place.formatted_address;
				
				var latitude = place.geometry.location.lat();
				
				var longitude = place.geometry.location.lng();  
				$('#latitude').val(latitude); 
				$('#longitude').val(longitude); 
			});
		}
		
	});

	</script>
@stop 