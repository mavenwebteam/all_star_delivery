@extends('layouts.adminmaster')
@section('title')
{{Config::get("Site.title")}} | Driver Manager
@stop
@section('content') 
<script type="text/javascript">
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });

    //----------search------------- 
    function searchItem() 
    {
        $.ajax({
            type: 'GET',
            url: '{{ route("admin.drivers.index") }}',
            data: $('#mySearchForm').serialize(),
            beforeSend: function(){
                $.LoadingOverlay("show");
            },
            success: function(data){
                $('#replace-div').html(data);
                $('.loading-top').fadeOut();
                $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);
                $.LoadingOverlay("hide");
                return false;
            }
        });
    };
    // -------reset search form----------
    function resetSearchForm(){
      $('#mySearchForm').trigger("reset");
      searchItem();
    }

    // -----------fetch data ajax pagination with laravel------------------------
    function fetchDataByPage(pageNo)
    {
      $.ajax({
      type: 'GET',
      url:"{{ url('admin/drivers?page=') }}"+pageNo,
      data: $('#mySearchForm').serialize(),
      beforeSend: function(){
          $.LoadingOverlay("show");
      },
      success: function(data){
        $('#replace-div').html(data);
        $('.loading-top').fadeOut();
        $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);
        $.LoadingOverlay("hide");
        return false;
      }
      });
    }

    function add_record() 
    {
      $.LoadingOverlay("show");
      var checklogin1 = checklogin();
      if(checklogin1  == true){
          $('#UserModal').html(''); $(".form-title").text('Add Driver');
          $('#UserModal').load('{{ route("admin.drivers.create") }}');
          $("#myModal").modal();
      }else{
        location.reload();
          $.LoadingOverlay("hide");
      } 
    }

    function edit_record(edit_id) 
    {
      $.LoadingOverlay("show");
      var checklogin1 = checklogin();
      if(checklogin1  == true){
          $('#UserModal').html(''); $(".form-title").text('Edit Driver');
          $('#UserModal').load('{{ URL::to("admin/drivers/") }}'+'/'+edit_id+'/edit');
          $("#myModal").modal();
          $.LoadingOverlay("hide");
      }else{
        location.reload();
          $.LoadingOverlay("hide");
      } 
    }

    function view_record(view_id) 
    {
      $.LoadingOverlay("show");
      var checklogin1 = checklogin();
      if(checklogin1  == true){
          $('#UserModal').html(''); $(".form-title").text('View Driver');
          $('#UserModal').load('{{ URL::to("admin/drivers/") }}'+'/'+view_id);
          $("#myModal").modal();
      }else{
        location.reload();
          $.LoadingOverlay("hide");
      } 
    }

    function loadPiece( href ) 
    {
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
              url: '{{ URL::to('/admin/drivers/status') }}',
          }).done(function( data ) 
          {   
            searchItem();
            if(data.class == 'success')
              {  $(".alert-success").remove();
              showMsg(data.message, "success");}
          });
      
      }else{
        location.reload();
          $.LoadingOverlay("hide");
      } 
        
    }

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
            type: "DELETE",
            url: '{{ URL::to('admin/drivers') }}'+'/'+id,
        }).done(function( data ) 
        {   
          searchItem();
          if(data.class == 'success')
          {  
            $(".alert-success").remove();
            showMsg(data.message, "success");
          }
        });
				}
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  		
        
  }

  $(document).on('click', '.pagination a', function(event){
    event.preventDefault();
    var pageNum = $(this).attr('href').split('page=')[1];
    fetchDataByPage(pageNum);
 });

 
</script>



<section class="content-header"><h1>Drivers</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Drivers</li>
  </ol>
</section>
<section class="" style="padding: 15px;">
  <div class="row">
    <div class="col-xs-12 page-user">
      <div class="box">
        <div class="box-body">
        {!! Form::open(array('route' => 'admin.drivers.index', 'method' => 'get','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
          <div class="col-xs-12 col-sm-6 col-md-3">
          <label></label>
            {!! Form::text('keyword',null, ['class' => 'form-control','placeholder' =>'Search' ]) !!}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3">
          <label></label>
            {!! Form::text('uu_id',null, ['class' => 'form-control','placeholder' => 'Driver Id']) !!}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-12">
              <label></label>
              <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="button" value="Search" onclick="searchItem()">
              <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="button">Reset</button>
              <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Product"><span class="btn-label icon fa fa-plus"></span> Add Driver</a>
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
              @include('admin.drivers.search')
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

