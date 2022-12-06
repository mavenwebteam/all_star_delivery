@extends('layouts.sub_admin_master')
@section('title')
All Star Delivery | Business Category
@stop
@section('content') 
<script type="text/javascript">
   var init = [];
   function search() {
       $.ajax({
           type: 'POST',
           url: '{{ URL::to('/sub-admin/business-category') }}',
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
   function checklogin(){
   var returnValue;
   $.ajax({
   type: "get",
   async: false,
   url: '{{ URL("sub-admin/checkuserlogin") }}',
   dataType: 'json',
   contentType: 'application/json; charset=utf-8',
   data: JSON.stringify({ name: name }),
   success: function (data) { //alert(data);
   	//returnValue = data.d;
   	if(data == 2){ 
   	returnValue = false;
   		//location.reload();
   		//$.LoadingOverlay("hide");
   		//return false;
   	}else{
   		//return true;
   		returnValue = true;
   	}
   }
   });
   return returnValue;
   }
   
   
   
   function add_record(){
       $.LoadingOverlay("show");
   var checklogin1 = checklogin();
   if(checklogin1  == true){
       $('#UserModal').html(''); $(".form-title").text('Add Business Category');
       $('#UserModal').load('{{ URL::to('/sub-admin/add-business-category') }}');
       $("#myModal").modal();
   $.LoadingOverlay("hide");
   }else{
   location.reload();
   $.LoadingOverlay("hide");
   } 	
   }
   
   function edit_record(edit_id) {
   
       $.LoadingOverlay("show");
   var checklogin1 = checklogin();
   if(checklogin1  == true){
       $('#UserModal').html(''); $(".form-title").text('Edit Business Category');
       $('#UserModal').load('{{ URL::to("/sub-admin/edit-business-category") }}'+'/'+edit_id);
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
       $('#UserModal').html(''); $(".form-title").text('View Business Category ');
       $('#UserModal').load('{{ URL::to('/sub-admin/view-business-category') }}'+'/'+view_id);
       $("#myModal").modal();
   $.LoadingOverlay("hide");
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
   function selectImportBrand() {
   
   
   $.LoadingOverlay("show");
   var checklogin1 = checklogin();
   if(checklogin1  == true){
   $('#UserModal').html(''); $(".form-title").text('Select XLS File to import');
   $('#UserModal').load('{{ URL("/sub-admin/import-businees-category") }}');
   $("#myModal").modal();
   }else{
   location.reload();
   $.LoadingOverlay("hide");
   }
   
   }
   
   function statusChange(id) 
   {
   
   var checklogin1 = checklogin();
   if(checklogin1  == true){
     $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                 }
             });
     $.ajax({
           dataType: 'json',
           data: { id:id}, 
           type: "POST",
           url: '{{ URL::to('/sub-admin/business-category-status') }}',
       }).done(function( data ) 
       {   
         search();
         if(data.class == 'success')
           {showMsg(data.message, "success");}
         
         
       });
   
   }else{
   location.reload();
   $.LoadingOverlay("hide");
   }
       
   }
   
   $(document).ready(function() {
   
   
       loadPiece( '{{ URL::to('/sub-admin/business-category') }}');
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
           url: '{{ URL::to('/sub-admin/business-category-remove') }}',
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
   <h1>Business Category</h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/sub-admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Business Category</li>
   </ol>
</section>
<section class="" style="padding: 15px;">
   <div class="row">
      <div class="col-xs-12 page-user">
         <div class="box">
            <div class="box-body">
               {!! Form::open(array('method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
               <div class="col-xs-12 col-sm-6 col-md-4">
                  <label></label>
                  {!! Form::text('name',null, ['class' => 'form-control','placeholder' => 'Name (en)' ]) !!}
               </div>
               <div class="col-xs-12 col-sm-6 col-md-8">
                  <label></label>
                  <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                  <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                  <!-- <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>-->
                  <!--<button type="button" style="margin-top: 20px;margin-left: 10px;" onclick="selectImportBrand();" class="btn btn-outline-primary pull-left"><i class="fa fa-file-excel-o"></i> Import XLS</button>-->
                  @if(in_array('subAdmin.add-business-category.index', $permissionData))
                  <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Business Category"><span class="btn-label icon fa fa-plus"></span> Add Business Category</a>
                  @endif
               </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
</section>
<div id="msg-data"> </div>
<section class="content">
   <div class="row">
      <div class="col-xs-12">
         <div class="box">
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
               @include('sub_admin.business-category.search')
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
@stop