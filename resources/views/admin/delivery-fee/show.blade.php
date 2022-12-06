@extends('layouts.adminmaster')
@section('title')
All Star Delivery | User Manager
@stop
@section('content') 
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get("Site.google_api_key");?>&libraries=places&language=en" type="text/javascript"></script> 

{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCnC4j2VEpmhzkDbSAPSG27BI4Ux5bwNrk&libraries=places&language=en" type="text/javascript"></script>  --}}
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
<script type="text/javascript">

  function add_record() {
        $.LoadingOverlay("show");
        var checklogin1 = checklogin();
        if(checklogin1  == true){
          $('#feeModel').html(''); $(".form-title").text('Add Deliver Fee');
          $('#feeModel').load('{{ URL::to('/admin/add-delivery-fee') }}');
          $("#myModal").modal();
        }else {
          location.reload();	
          $.LoadingOverlay("hide");
      } 
    }

  function edit_record(edit_id) {

      $.LoadingOverlay("show");		var checklogin1 = checklogin();		if(checklogin1  == true){
      $('#feeModel').html(''); $(".form-title").text('Edit Delivery Fee');
      $('#feeModel').load('{{ URL::to('/admin/edit-delivery-fee') }}'+'/'+edit_id);
      $("#myModal").modal();		}else{			location.reload();			$.LoadingOverlay("hide");		} 
  }
</script>
    <section class="content-header">
      <h1>Delivery Fee</h1>
        <ol class="breadcrumb">
          <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Delivery Fee</li>
        </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
            <div Class="col-md-8">
              <form method="post" action="{{ route('admin.update.max-rdius') }}">
                @csrf
                @method('PUT')
                <h4>Max Delivery Radius: <input type="text" value="{{ $radius }}" name="delivery_max_radius"/>  KM 
                  @error('delivery_max_radius')
                      <p class="text-danger">{{ $message }}</p>
                  @enderror         
                <button type="submit" class="btn btn-primary" style="display: inline">Update</button>
              </h4>  
              </form>
            </div>
            <div Class="col-md-4">
              {{-- <a href="javascript:" class="custom-btn pull-right btn btn-outline-primary btn-labeled" onclick="add_record();" title="Add User"><span class="btn-label icon fa fa-plus"></span>Add Delivery Fee</a> --}}
            </div>
            </div>
          </div>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer Delivery Fee</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('admin.delivery-fee.search')
            </div>
            <!-- /.box-body -->
          </div>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Driver Delivery Fee</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('admin.delivery-fee.driver-table')
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
                    </i>&nbsp;&nbsp;<span class='form-title'></span>
                  </h4>
                  <button type="button" class="close subbtn" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body" id="feeModel">

              </div>
          </div>
      </div>
    </div>    
  </div>
</div>

<script>
  $(function () {
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