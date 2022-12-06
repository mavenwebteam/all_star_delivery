@extends('layouts.adminmaster')

@section('title')

{{Config::get("Site.title")}} | Item Category Manager

@stop

@section('content') 

<script type="text/javascript">



    var init = [];



    function search() {



        $.ajax({

            type: 'POST',

            url: '{{ URL::to('/admin/item-category') }}',

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
        var UserSearchName = $.trim($('#UserSearchName').val());

        var UserEmail = $.trim($('#UserEmail').val());           

        var UserMobile = $.trim($('#UserMobile').val());             

        var UserAddress = $.trim($('#UserAddress').val());           

        var UserCreated = $.trim($('#UserCreated').val());           

        var UserTodate = $.trim($('#UserTodate').val());           

        var UserSearchStatus = $.trim($('#UserSearchStatus').val());          

        window.location.href = '/karicare-admin/export-users?search_name='+UserSearchName+'&email='+UserEmail+'&mobile='+UserMobile+'&address='+UserAddress+'&created='+UserCreated+'&todate='+UserTodate+'&search_status='+UserSearchStatus; 
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}    

    }



    function add_record() {



        $.LoadingOverlay("show");
		var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('Add Item Category');

        $('#UserModal').load('{{ URL::to('/admin/add-item-category') }}');

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
        $('#UserModal').html(''); $(".form-title").text('Edit Item Category');

        $('#UserModal').load('{{ URL::to('/admin/edit-item-category') }}'+'/'+edit_id);

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
        $('#UserModal').html(''); $(".form-title").text('View Item Category');

        $('#UserModal').load('{{ URL::to('/admin/view-item-category') }}'+'/'+view_id);

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

      $.ajaxSetup({

                  headers: {

                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

                  }

              });

      $.ajax({

            dataType: 'json',

            data: { id:id}, 

            type: "POST",

            url: '{{ URL::to('/admin/item-category-status') }}',

        }).done(function( data ) 

        {   

          search();

          if(data.class == 'success')

            {showMsg(data.message, "success");}

          

          

        });

        

    }



function selectImportBrand() {



    $.LoadingOverlay("show");

    $('#UserModal').html(''); $(".form-title").text('Select XLS File to import');

    $('#UserModal').load('{{ URL::to("/admin/import-item-category") }}');

    $("#myModal").modal();

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

            url: '{{ URL::to('/admin/item-category-remove') }}',

        }).done(function( data ) 

        {   

          search();

          if(data.class == 'success')

            {showMsg(data.message, "success");}

          

          

        });

        

    }



    $(document).ready(function() {





        loadPiece( '{{ URL::to('/admin/item-category') }}');

    })

function remove_record(id) 

    { 

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

            url: '{{ URL::to('/admin/item-category-remove') }}',

        }).done(function( data ) 

        {   

          search();

           if(data.class == 'success')

            {showMsg(data.message, "success");}

          

          

        });

			   }

    }



</script>

<section class="content-header"><h1>Item Category</h1>

      <ol class="breadcrumb">

        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Item Category</li>

      </ol>

</section>

<section class="" style="padding: 15px;">

      <div class="row">

        <div class="col-xs-12 page-user">

          <div class="box">

            <div class="box-body">

            {!! Form::open(array('url' => '/admin/users', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}

            <div class="col-xs-12 col-sm-6 col-md-3">
              <label></label>
              <select name="business_category" class="form-control">
                  <option value="">-Business Category-</option>
                  @foreach($businessCategories as $category)
                      <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                  @endforeach
              </select>
          </div>

            <div class="col-xs-12 col-sm-6 col-md-3">

            <label></label>

              {!! Form::text('name_en',null, ['class' => 'form-control','placeholder' => 'Name (en)' ]) !!}

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

           <!-- <div class="col-xs-12 col-sm-6 col-md-3">

            <label></label>

            <?php $helper=new App\Helpers;?>

            {!! Form::select('type',$helper->SelectProductCategoryType(),null, ['class' => 'form-control','id'=>'','onkeypress' => 'error_remove()']) !!}

           </div>-->

           <div class="col-xs-12 col-sm-6 col-md-12">

                <label></label>

                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">

                <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>

                <!-- <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>-->

				<!-- <button type="button" style="margin-top: 20px;margin-left: 10px;" onclick="selectImportBrand();" class="btn btn-outline-primary pull-left"><i class="fa fa-file-excel-o"></i> Import XLS</button>-->

                <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Item Category"><span class="btn-label icon fa fa-plus"></span> Add Item Category</a>

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

                 @include('admin.item-category.search')

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