@extends('layouts.vendormaster')
@section('title')
{{Config::get("Site.title")}} | Menu Manager
@stop
@section('content') 
<script type="text/javascript">
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });

    //----------search------------- 
    function searchItem() {
        $.ajax({
            type: 'GET',
            url: '{{ route("vendor.menu-manager.index") }}',
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

    function resetSearchForm(){
      $("#mySearchForm").trigger("reset");
      searchItem();
    }
    // -----------fetch data ajax pagination with laravel------------------------
    function fetchDataByPage(pageNo)
    {
      $.ajax({
      type: 'GET',
      url:"{{ url('vendor/menu-manager?page=') }}"+pageNo,
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

    function add_record() {
      $.LoadingOverlay("show");
      var checklogin1 = checklogin();
      if(checklogin1  == true){
          $('#UserModal').html(''); $(".form-title").text('Add Item');
          $('#UserModal').load('{{ route("vendor.menu-manager.create") }}');
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
          $('#UserModal').html(''); 
          $(".form-title").text("{{ __('vendor.edit_item') }}");
          $('#UserModal').load('{{ URL::to("vendor/menu-manager/") }}'+'/'+edit_id+'/edit');
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
          $('#UserModal').html(''); $(".form-title").text("{{ __('vendor.view_item') }}");
          $('#UserModal').load('{{ URL::to("vendor/menu-manager/") }}'+'/'+view_id);
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
              url: '{{ URL::to('/vendor/menu-manager/product-status') }}',
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

    function changeStockStates(id) 
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
              url: '{{ URL::to('/vendor/menu-manager/product-stock-status') }}',
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
            url: '{{ URL::to('vendor/menu-manager') }}'+'/'+id,
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



<section class="content-header"><h1>{{ __('vendor.items') }}</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> {{ __('vendor.home') }}</a></li>
    <li class="active">{{ __('vendor.product') }}</li>
  </ol>
</section>
<section class="" style="padding: 15px;">
  <div class="row">
    <div class="col-xs-12 page-user">
      <div class="box">
        <div class="box-body">
        {!! Form::open(array('route' => 'vendor.menu-manager.index', 'method' => 'get','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}
          <div class="col-xs-12 col-sm-6 col-md-3">
          <label></label>
            {!! Form::text('name',null, ['class' => 'form-control','placeholder' => trans('vendor.product_name') ]) !!}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3">
          <label></label>
            {!! Form::text('uu_id',null, ['class' => 'form-control','placeholder' => trans('vendor.item_id')]) !!}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3">
            <label></label>
            <select name="item_category" class="form-control">
              <option value="">{{ __('vendor.choose_item_category') }}</option> 
              @foreach($itemCategory as $category)
                <option value="{{ $category->id }}">{{ $category->name_en }}</option> 
              @endforeach
            </select>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-12">
              <label></label>
              <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="button" value="{{ __('vendor.search_btn') }}" onclick="searchItem()">
              <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="button">{{ __('vendor.reset_btn') }}</button>
              <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Product"><span class="btn-label icon fa fa-plus"></span> {{ __('vendor.add_item_btn') }}</a>
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
              @include('vendor.product.search')
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
 

  // $(function () {
  //   //Date picker
  //   $('#datepicker').datepicker({
  //     autoclose: true
  //   })
  //   $('#datepicker2').datepicker({
  //     autoclose: true
  //   })
    
  // });

  

</script>
@stop 