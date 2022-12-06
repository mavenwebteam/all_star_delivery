@extends('layouts.sub_admin_master')
@section('title')
{{Config::get("Site.title")}} | Promocode Manager
@stop
@section('content') 
<section class="content-header"><h1>Promocode</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Promocode</li>
  </ol>
</section>
<section class="" style="padding: 15px;">
  <div class="row">
    <div class="col-xs-12 page-user">
      <div class="box">
        <div class="box-body">
          {{-- search form start --}}
          {!! Form::open(array('method' => 'get','name'=>'mySearchForm','files'=>true,'id' => 'promocodeSearchForm')) !!}
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              {!! Form::text('date',null, ['class' => 'datepicker form-control','placeholder' => 'Promocode Date','autocomplate'=>'off' ]) !!}
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
              <select class="form-control" name="status">
                <option value="">-Choose Status-</option>
                <option value="Active">Active</option>
                <option value="Deactive">Deactive</option>
              </select>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-12">
                <label></label>
                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="button" value="{{ __('vendor.search_btn') }}" onclick="searchItem()">
                <button onclick="resetSearchForm()" style="margin-top: 20px;margin-left: 10px;" class="btn btn-outline-success pull-left" type="button">Reset</button>
                @if(in_array('subAdmin.promocode.create', $permissionData))
                <a href="javascript:" class="custom-btn btn pull-right btn-outline-primary btn-labeled" onclick="add_record();" title="Add Promocode" data-toggle="tooltip" data-placement="top"><span class="btn-label icon fa fa-plus"></span> Add </a>
                @endif
            </div>
            
          {!! Form::close() !!}
          {{-- search form end --}}
         
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
              @include('sub_admin.promocode.search')
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
@stop 

@push('script')


<script type="text/javascript">
$(function () {
    $('.datepicker').datepicker({
      autoclose: true,
    });
  });

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

  //----------search------------- 
  function searchItem() {
    $.ajax({
        type: 'GET',
        url: '{{ route("subAdmin.promocode.index") }}',
        data: $('#promocodeSearchForm').serialize(),
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

  function resetSearchForm(){
    $("#promocodeSearchForm").trigger("reset");
    searchItem();
  }
  // -----------fetch data ajax pagination with laravel------------------------
  // function fetchDataByPage(pageNo)
  // {
  //   $.ajax({
  //   type: 'GET',
  //   url:"{{ url('sub-admin/banners?page=') }}"+pageNo,
  //   data: {},
  //   beforeSend: function(){
  //       $.LoadingOverlay("show");
  //   },
  //   success: function(data){
  //     $('#replace-div').html(data);
  //     $('.loading-top').fadeOut();
  //     $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);
  //     $.LoadingOverlay("hide");
  //     return false;
  //   }
  //   });
  // }

  function add_record() 
  {
    $.LoadingOverlay("show");
    var checklogin1 = checklogin();
    if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('Add Promocode');
        $('#UserModal').load('{{ route("subAdmin.promocode.create") }}');
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
        $('#UserModal').html(''); 
        $(".form-title").text('Edit Promocode');
        $('#UserModal').load('{{ URL::to("sub-admin/promocode/") }}'+'/'+edit_id+'/edit');
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
              data: {},
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
            url: '{{ URL::to('/sub-admin/promocode/status') }}',
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
            url: '{{ URL::to('sub-admin/promocode') }}'+'/'+id,
        }).done(function( data ) 
        {   
          searchItem();
          if(data.class == 'success')
          {
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
@endpush

