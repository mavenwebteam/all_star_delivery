@extends('layouts.sub_admin_master')

@section('title')

All Star Delivery | Email Template

@stop

@section('content') 

<script type="text/javascript">



    var init = [];



    function search() {



        $.ajax({

            type: 'POST',

            url: '{{ URL::to('/sub-admin/email-templates') }}',

            data: $('#mySearchForm').serialize(),

            beforeSend: function(){

                $.LoadingOverlay("show");

            },

            success: function(msg){

              

               $('#search-data').html(msg);

                $('#replace-div').html(msg);

                $('.loading-top').fadeOut();

                $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);

                $.LoadingOverlay("hide");

                return false;

            }

        });

    }



    function exportData() {

        var UserSearchName = $.trim($('#UserSearchName').val());

        var UserEmail = $.trim($('#UserEmail').val());           

        var UserMobile = $.trim($('#UserMobile').val());             

        var UserAddress = $.trim($('#UserAddress').val());           

        var UserCreated = $.trim($('#UserCreated').val());           

        var UserTodate = $.trim($('#UserTodate').val());           

        var UserSearchStatus = $.trim($('#UserSearchStatus').val());          

        window.location.href = '/karicare-admin/export-users?search_name='+UserSearchName+'&email='+UserEmail+'&mobile='+UserMobile+'&address='+UserAddress+'&created='+UserCreated+'&todate='+UserTodate+'&search_status='+UserSearchStatus;    

    }



    function add_record() {



        $.LoadingOverlay("show");

        $('#UserModal').html(''); $(".form-title").text('Add Content');

        $('#UserModal').load('{{ URL::to('/sub-admin/add-content') }}');

        $("#myModal").modal();

    }



    function edit_record(edit_id) {


      
        $.LoadingOverlay("show");
		 var checklogin1 = checklogin();
		if(checklogin1  == true){
        $('#UserModal').html(''); $(".form-title").text('Edit Email Templates');

        $('#UserModal').load('{{ URL::to('/sub-admin/edit-email-templates') }}'+'/'+edit_id);

        $("#myModal").modal();
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}    

    }



    function view_record(view_id) {



        $.LoadingOverlay("show");

        $('#UserModal').html(''); $(".form-title").text('View User');

        $('#UserModal').load('{{ URL::to('/sub-admin/view-content') }}'+'/'+view_id);

        $("#myModal").modal();

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

            url: '{{ URL::to('/sub-admin/content-remove') }}',

        }).done(function( data ) 

        {   

          search();

          if(data.class == 'success')

            {showMsg(data.message, "success");}

          

          

        });

        

    }



    $(document).ready(function() {





        loadPiece( '{{ URL::to('/sub-admin/email-templates') }}');

    })



</script>

<section class="content-header"><h1>Email Templates</h1>

      <ol class="breadcrumb">

        <li><a href="{{url('/sub-admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Email Templates</li>

      </ol>

</section>

<section class="" style="padding: 15px;">

      <div class="row">

        <div class="col-xs-12 page-user">

          <div class="box">

            <div class="box-body">

            {!! Form::open(array('url' => '/sub-admin/content', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'mySearchForm')) !!}



            <div class="col-xs-12 col-sm-6 col-md-4">

            <label></label>

              {!! Form::text('title',null, ['class' => 'form-control','placeholder' => 'Title' ]) !!}

            </div>

            <div class="col-xs-12 col-sm-6 col-md-8">

                <label></label>

                <input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">

                <button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>

                <!-- <button style="margin-top: 20px;margin-left: 10px;" onclick="exportData();" class="btn btn-outline-danger pull-left" type="submit">Export</button>-->

                <!-- <a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Content"><span class="btn-label icon fa fa-plus"></span>Add Content</a> -->

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

            <div class="box-header">

              <h3 class="box-title">Email Templates Table</h3>

            </div>

            <!-- /.box-header -->

            <div class="box-body" id="replace-div">

                 @include('sub_admin.email-templates.search')

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

                <button type="button" class="close subbtn" data-dismiss="modal" aria-hidden="true">??</button>

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