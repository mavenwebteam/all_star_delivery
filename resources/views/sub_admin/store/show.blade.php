@extends('layouts.sub_admin_master')
@section('title')
All Star Delivery | Store Manager
@stop 
@section('content') 
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get("Site.google_api_key");?>&libraries=places&language=en" type="text/javascript"></script> 
<script type="text/javascript">
   var init = [];
   
   function search() {
   
       $.ajax({
           type: 'POST',
           url: '{{ URL::to('/sub-admin/store')}}',
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
   
   function exportData() {		var checklogin1 = checklogin();		if(checklogin1  == true){
       var UserSearchName = $.trim($('#UserSearchName').val());
       var UserEmail = $.trim($('#UserEmail').val());           
       var UserMobile = $.trim($('#UserMobile').val());             
       var UserAddress = $.trim($('#UserAddress').val());           
       var UserCreated = $.trim($('#UserCreated').val());           
       var UserTodate = $.trim($('#UserTodate').val());           
       var UserSearchStatus = $.trim($('#UserSearchStatus').val());          
       window.location.href = '/karicare-admin/export-users?search_name='+UserSearchName+'&email='+UserEmail+'&mobile='+UserMobile+'&address='+UserAddress+'&created='+UserCreated+'&todate='+UserTodate+'&search_status='+UserSearchStatus;    				}else{			location.reload();				$.LoadingOverlay("hide");		} 			  
   }
   
   function add_record() {
     alert("fddf");die;
       $.LoadingOverlay("show");		var checklogin1 = checklogin();		if(checklogin1  == true){
       $('#UserModal').html(''); $(".form-title").text('Add Store');
       $('#UserModal').load('{{ URL::to('/sub-admin/add-store')}}');
       $("#myModal").modal();		}else{			location.reload();				$.LoadingOverlay("hide");		} 
   }
   
   function edit_record(edit_id) {
   
       $.LoadingOverlay("show");		var checklogin1 = checklogin();		if(checklogin1  == true){
       $('#UserModal').html(''); $(".form-title").text('Edit Store');
       $('#UserModal').load('{{ URL::to('/sub-admin/edit-store') }}'+'/'+edit_id);
       $("#myModal").modal();		}else{			location.reload();				$.LoadingOverlay("hide");		} 
   }
   
   function view_record(view_id) {
   
       $.LoadingOverlay("show");		var checklogin1 = checklogin();		if(checklogin1  == true){
       $('#UserModal').html(''); $(".form-title").text('View Store');
       $('#UserModal').load('{{ URL::to('/sub-admin/view-store') }}'+'/'+view_id);
       $("#myModal").modal();		}else{			location.reload();				$.LoadingOverlay("hide");		} 
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
   {		var checklogin1 = checklogin();		if(checklogin1  == true){
     $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                 }
             });
     $.ajax({
           dataType: 'json',
           data: { id:id}, 
           type: "POST",
           url: '{{ URL::to('/sub-admin/store-status') }}',
       }).done(function( data ) 
       {   
         search();
         if(data.class == 'success')
           {showMsg(data.message, "success");}
         
         
       });
       }else{			location.reload();				$.LoadingOverlay("hide");		} 			  
   }
   function aproveStore(id) 
   {      var checklogin1 = checklogin();		if(checklogin1  == true){
     $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                 }
             });
     $.ajax({
           dataType: 'json',
           data: { id:id}, 
           type: "POST",
           url: '{{ URL::to('/sub-admin/store-aprove') }}',
   beforeSend: function(){
               $.LoadingOverlay("show");
           },
       }).done(function( data ) 
       {   
         search();
         if(data.class == 'success')
           {   
   $.LoadingOverlay("hide");
   		showMsg(data.message, "success");
   
   location.reload();
   }
         
         
       });
       }else{			location.reload();				$.LoadingOverlay("hide");		} 
   }
   function view_user_record(view_id) {
   
       $.LoadingOverlay("show");		var checklogin1 = checklogin();		if(checklogin1  == true){
       $('#UserModal').html(''); $(".form-title").text('View Vendor');
       $('#UserModal').load('{{ URL::to('/sub-admin/view-vendor') }}'+'/'+view_id);
       $("#myModal").modal();		}else{			location.reload();				$.LoadingOverlay("hide");		} 
   }
   function remove_record(id) 
   { 		var checklogin1 = checklogin();		if(checklogin1  == true){
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
           url: '{{ URL::to('/sub-admin/store/store-remove') }}',
       }).done(function( data ) 
       {   
         search();
          if(data.class == 'success')
           {showMsg(data.message, "success");}
         
         
       });
     }			   		}else{				location.reload();				$.LoadingOverlay("hide");		}   
   }
   
   $(document).ready(function() {
   
   
       loadPiece( '{{ URL::to('/sub-admin/store') }}');
   })
   
</script>
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
<section class="content-header">
   <h1>Stores</h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/sub-admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Store</li>
   </ol>
</section>
<section class="" style="padding: 15px;">
   <div class="row">
      <div class="col-xs-12 page-user">
         <div class="box">
            <div class="box-body">
               {!! Form::open(array('url' => '/sub-admin/store', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
               <div class="col-xs-12 col-sm-6 col-md-3">
                  <label></label>
                  {!! Form::text('name',null, ['class' => 'form-control ','placeholder' => 'Store Name' ]) !!}
               </div>
               <div class="col-xs-12 col-sm-6 col-md-3">
                  <label></label>
                  <div class="input-group date">
                     <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                     </div>
                     <input type="text" class="form-control pull-right" id="datepicker" readonly name="start_date" placeholder="Start Date" readonly>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-6 col-md-3">
                  <label></label>
                  <div class="input-group date">
                     <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                     </div>
                     <input type="text" class="form-control pull-right" id="datepicker2" readonly name="end_date" placeholder="End Date" readonly>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-6 col-md-3">
                  <label></label>
                  <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                  <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                  @if(in_array('subAdmin.store.create', $permissionData))
                  <a href="{{ URL::to('/sub-admin/store/add-store') }}" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled"  title="Add Store"><span class="btn-label icon fa fa-plus"></span> Add Store</a>
                  @endif
               </div>
               {!! Form::close() !!}
            </div>
            <br/>
         </div>
      </div>
   </div>
</section>
<div id="msg-data" > </div>
<section class="content">
   <div class="row">
      <div class="col-xs-12">
         <div class="box">
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
               @include('sub_admin.store.search')
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
</div>
</div>
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