@extends('layouts.adminmaster')
@section('title')
All Star Delivery | Order Report
@stop
@section('content') 
<script type="text/javascript">
   var init = [];
   function search() {
       $.ajax({
           type: 'GET',
           url: '{{ URL::to('/admin/report/store') }}',
           data: $('#mySearchForm').serialize(),
           beforeSend: function(){
               $.LoadingOverlay("show");
           },
           success: function(msg){              
               $('#replace-div').html(msg);
               $.LoadingOverlay("hide");
               return false;
           }
       });
   }
   
   function exportData() {
      var checklogin1 = checklogin();
      if(checklogin1  == true){
         var startDate = $.trim($('#datepicker').val());           
         var endDate = $.trim($('#datepicker2').val());
         var store_type = $.trim($('#store_type').val());
         window.location.href = '{{ url('/') }}'+'/admin/report/export-store?startDate='+startDate+'&endDate='+endDate+'&store_type='+store_type;  
      }else{
         location.reload();
         $.LoadingOverlay("hide");
      }  
   }

   function checklogin(){
   var returnValue;
   $.ajax({
   type: "get",
   async: false,
   url: '{{ URL("admin/checkuserlogin") }}',
   dataType: 'json',
   contentType: 'application/json; charset=utf-8',
   data: JSON.stringify({ name: name }),
   success: function (data) { //alert(data);
   	//returnValue = data.d;
   	if(data == 2){ 
   	returnValue = false;
   	}else{
   		returnValue = true;
   	}
   }
   });
   return returnValue;
   }
   
   $('body').on('click', 'ul.pagination a', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      $.ajax({
         type: 'GET',
         url: url,
         beforeSend:  function(){
               $.LoadingOverlay("show");
         },
         data: ($('#mySearchForm').serialize()),
         success: function(msg){
               $('#replace-div').html(msg);
               $.LoadingOverlay("hide");
               return false;
         }
      });
      return false;
   });

   $("#mySearchForm").on('submit', function(event){
      event.preventDefault();
      search();
   });
</script>
<section class="content-header">
   <h1>Store Report</h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('admin.report')}}"><i class="fa fa-dashboard"></i> Report</a></li>
      <li class="active">Store Report</li>
   </ol>
</section>
<section class="" style="padding: 15px;">
   <div class="row">
      <div class="col-xs-12 page-user">
         <div class="box">
            <div class="box-body">
               {!! Form::open(array('method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
                  
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
                     <div class="input-group">
                        <select name="store_type" id="store_type" class="form-control">
                           <option value="">-Store Type-</option>
                           @foreach ($businessCategories as $category)
                           <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               <div class="col-xs-12 col-sm-6 col-md-3">
                  <label></label>
                  <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                  <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                  <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-info pull-left" type="submit"><i class="fa fa-cloud-download" aria-hidden="true"></i> Export</button>
                 
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
               @include('admin.report.store_table')
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
   });
</script>
@stop