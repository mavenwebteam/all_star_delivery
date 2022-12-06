@extends('layouts.sub_admin_master') @section('title') All Star Delivery | Vendor Manager @stop @section('content')

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get("Site.google_api_key");?>&libraries=places&language=en" type="text/javascript"></script>



<style>

.pac-container {

	background-color: #fff;

	position: absolute!important;

	z-index: 10000;

	border-radius: 2px;

	border-top: 1px solid #d9d9d9;

	font-family: Arial, sans-serif;

	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);

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

		url: '{{ URL::to('/sub-admin/vendor') }}',

		data: $('#mySearchForm').serialize(),

		beforeSend: function() {

			$.LoadingOverlay("show");

		},

		success: function(msg) {

			$('#replace-div').html(msg);

			$('.loading-top').fadeOut();

			$('html,body').animate({

				scrollTop: $('.page-user').offset().top - 0

			}, 1400);

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

	window.location.href = '{{ URL::to('/sub-admin/export-vendor') }}' + '?user_search_name=' + user_search_name + '&user_search_uniq_id=' + user_search_uniq_id + '&datepicker=' + datepicker + '&datepicker2=' + datepicker2 + '&search_user_type=' + search_user_type + '&search_user_status=' + search_user_status;

}



function add_record() {

	$.LoadingOverlay("show");

	$('#UserModal').html('');

	$(".form-title").text('Add Vendor');

	$('#UserModal').load('{{ URL::to('/sub-admin/add-vendor')}}');

	$("#myModal").modal();

}



function edit_record(edit_id) {

	$.LoadingOverlay("show");

	$('#UserModal').html('');

	$(".form-title").text('Edit Vendor');

	$('#UserModal').load('{{ URL::to('/sub-admin/edit-vendor')}}'+'/'+edit_id);

	$("#myModal").modal();

}



function view_record(view_id) {

	$.LoadingOverlay("show");

	$('#UserModal').html('');

	$(".form-title").text('View Vendor');

	$('#UserModal').load('{{ URL::to('/sub-admin/view-vendor') }}'+'/' +view_id);

	$("#myModal").modal();

}



function loadPiece(href) {
	$('body').on('click', 'ul.pagination a', function() {
		var getPage = $(this).attr('href').split('page=')[1];
		var go_url = href + '?page=' + getPage;
		$.ajax({
			type: 'POST',
			url: go_url,
			beforeSend: function() {
				$.LoadingOverlay("show");
			},
			data: ($('#mySearchForm').serialize()),
			success: function(msg) {
				$('html,body').animate({
					scrollTop: $('.page-user').offset().top - 0
				}, 1400);
				$('#replace-div').html(msg);
				$.LoadingOverlay("hide");
				return false;
			}
		});
		return false;
	});
}



function statusChange(id) {

	$.ajaxSetup({

		headers: {

			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

		}

	});

	$.ajax({

		dataType: 'json',

		data: {

			id: id

		},

		type: "POST",

		url: '{{ URL::to('/sub-admin/vendor-status ')}}',

	}).done(function(data) {

		search();

		if(data.class == 'success') {

			showMsg(data.message, "success");

		}

	});

}

$(document).ready(function() {

	loadPiece('{{ URL::to('/sub-admin/vendor')}}');

})

</script>

<section class="content-header">

	<h1>Vendors</h1>

	<ol class="breadcrumb">

		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

		<li class="active">Vendors</li>

	</ol>

</section>

<section class="" style="padding: 15px;">

	<div class="row">

		<div class="col-xs-12 page-user">

			<div class="box">

				<div class="box-body"> {!! Form::open(array('url' => '/sub-admin/search-vendor', 'method' => 'post','name'=>'mySearchForm','files'=>true,'novalidate' => 'novalidate','id' => 'mySearchForm')) !!}

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label> {!! Form::text('first_name',null, ['class' => 'form-control','id'=>'first_name','placeholder'=>'First Name']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label> {!! Form::text('last_name',null, ['class' => 'form-control','id'=>'last_name','placeholder'=>'Last Name']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label> {!! Form::text('email',null, ['class' => 'form-control','id'=>'user_search_email','placeholder'=>'Email']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label> {!! Form::text('mobile',null, ['class' => 'form-control','id'=>'user_search_mobile','placeholder'=>'Mobile']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label> {!! Form::text('uu_id',null, ['class' => 'form-control','id'=>'user_search_uniq_id','placeholder'=>'Unique Id']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label> {!! Form::text('rating',null, ['class' => 'form-control','id'=>'user_search_name','placeholder'=>'Rating']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-3">
						<label></label>
						<div class="input-group date">
							<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
							<input type="text" class="form-control pull-right" id="datepicker" name="start_date" placeholder="Start Date" readonly /> 
						</div>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label>

						<div class="input-group date">

							<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

							<input type="text" class="form-control pull-right" id="datepicker2" name="end_date" placeholder="End Date" readonly /> 
						</div>

					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 ">

						<label></label>

						<?php $helper=new App\Helpers;?> {!! Form::select('status',$helper->SelectUserStatus(),null, ['class' => 'form-control','required'=>'required','id'=>'search_user_status','onkeypress' => 'error_remove()']) !!} 
					</div>

					<div class="col-xs-12 col-sm-6 col-md-3">

						<label></label>

						<?php $helper=new App\Helpers;?> {!! Form::select('perpage',$helper->SelectPerPageData(),null, ['class' => 'form-control','id'=>'search_user_perpage','onkeypress' => 'error_remove()']) !!} </div>

					<div class="col-xs-12 col-sm-6 col-md-12">

						<label></label>

						<input style="margin-top: 20px;" id="search" class="btn btn-outline-primary pull-left" type="submit" value="Search">

						<button style="margin-top: 20px;margin-left: 10px;" onclick="resetSearchForm();" class="btn btn-outline-success pull-left" type="submit">Reset</button>
						@if(in_array('subAdmin.add-vendor.index', $permissionData))
							<a href="javascript:" style="margin-top: 20px;" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add Vendor"><span class="btn-label icon fa fa-plus"></span> Add Vendor</a> 
						@endif
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

				<div class="box-body" id="replace-div"> @include('sub_admin.vendor.search') </div>

				<!-- /.box-body -->

			</div>

		</div>

		<!-- /.col -->

	</div>

	<!-- /.row -->

</section>

<div id="myModal" class="modal fade form-modal" data-keyboard="false" role="dialog" style="display: none;">

	<div class="modal-dialog modal-lg modal-big">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">



                    <i class="fa fa-user"></i>&nbsp;&nbsp;<span class='form-title'></span>



                </h4>

				<button type="button" class="close subbtn" data-dismiss="modal" aria-hidden="true">×</button>

			</div>

			<div class="modal-body" id="UserModal"> </div>

		</div>

	</div>

</div>

</div>

</div>

<script>

$(function() {

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