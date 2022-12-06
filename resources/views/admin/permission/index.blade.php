@extends('layouts.adminmaster')
@section('title')
{{Config::get("Site.title")}} | Banner Manager
@stop
@section('content') 
<script type="text/javascript">
    // Update Permission
    $(document).on('click','.permission',function(){
      var user_id = {{ $userId }} ;
      var permission_id = $(this).val();
      $.ajax({
          url: '{{ route("admin.permission.store") }}',
          type: 'POST',
          data: {user_id, permission_id},
          dataType: 'json',
          success: function( data ) {
             if(data.success){
               toastr.success(data.message);
              }else{
               toastr.error(data.errors);
             }
          },
          error: function (xhr) {
              $.each(xhr.responseJSON.errors, function(key,value) {                  
                  toastr.error(value)
              });  
          }
      });
    });  

    // -----------fetch data ajax pagination with laravel------------------------
    function fetchDataByPage(pageNo)
    {
      let userId = '{{ base64_encode($userId) }}';
      let title = $("#title").val();
      $.ajax({
      type: 'GET',
      url:"{{ url('admin/permission?page=') }}"+pageNo+'&user='+userId+'&title='+title,
      data: {},
      beforeSend: function(){
          $.LoadingOverlay("show");
      },
      success: function(data){
        $('#replace-div').html(data);
        $.LoadingOverlay("hide");
        return false;
      }
      });
    }
    $(document).on('click', '.pagination a', function(event){
      event.preventDefault();
      var pageNum = $(this).attr('href').split('page=')[1];
      fetchDataByPage(pageNum);
    }); 

    function search() {
      let userId = '{{ base64_encode($userId) }}';
      $.ajax({
          type: 'GET',
          url: '{{ url('admin/permission') }}' + '?user='+userId,
          data: $('#mySearchForm').serialize(),
          beforeSend: function() {
              $.LoadingOverlay("show");
          },
          success: function(msg) {
              $('#replace-div').html(msg);
              $.LoadingOverlay("hide");
              return false;
          }
      });
    }

</script>



<section class="content-header"><h1>Permissions</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Permissions</li>
  </ol>
</section>
<section class="" style="padding: 15px;">
  <div class="row">
     <div class="col-xs-12 page-user">
        <div class="box">
           <div class="box-body">
              {!! Form::open(array('url' => '/admin/permissions', 'method' => 'post','name'=>'mySearchForm', 'id' => 'mySearchForm')) !!}
              <div class="col-xs-12 col-sm-6 col-md-4">
                 <label></label>
                 {!! Form::text('title',null, ['class' => 'form-control','placeholder' => 'Perminssion', 'id'=>'title' ]) !!}
              </div>
              <div class="col-xs-12 col-sm-6 col-md-8">
                 <label></label>
                 <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">
                 <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
                 <!-- <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>
                    <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Content"><span class="btn-label icon fa fa-plus"></span>Add Content</a>-->
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
        <div class="box-body" id="replace-div">
              @include('admin.permission.search')
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

