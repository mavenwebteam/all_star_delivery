@extends('layouts.vendormaster')
@section('title')
BRINGOO | Vendor Review Manager
@stop
@section('content') 

</style> 
<script type="text/javascript">

    var init = [];

    function search() {
      

        $.ajax({
            type: 'POST',
            url: '{{ URL::to('/vendor/vendor-rating') }}',
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
        var user_search_name = $.trim($('#user_search_name').val());
        var user_search_uniq_id = $.trim($('#user_search_uniq_id').val());   
        var datepicker = $.trim($('#datepicker').val());   
        var datepicker2 = $.trim($('#datepicker2').val());   
        var search_user_type = $.trim($('#search_user_type').val());   
        var search_user_status = $.trim($('#search_user_status').val());   


             
        window.location.href = '{{ URL::to('/vendor/export-picker') }}'+'?user_search_name='+user_search_name+
        '&user_search_uniq_id='+user_search_uniq_id+
        '&datepicker='+datepicker+
        '&datepicker2='+datepicker2+
        '&search_user_type='+search_user_type+
        '&search_user_status='+search_user_status;    
    }

    function add_record() {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Add Picker');
        $('#UserModal').load('{{ URL::to('/vendor/add-picker') }}');
        $("#myModal").modal();
    }

    function edit_record(edit_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('Edit Picker');
        $('#UserModal').load('{{ URL::to('/vendor/edit-picker') }}'+'/'+edit_id);
        $("#myModal").modal();
    }

    function view_record(view_id) {

        $.LoadingOverlay("show");
        $('#UserModal').html(''); $(".form-title").text('View Picker');
        $('#UserModal').load('{{ URL::to('/vendor/view-picker') }}'+'/'+view_id);
        $("#myModal").modal();
    }

    function loadPiece( href ) {

        $('body').on('click', 'ul.pagination a', function() {
          
            //var getPage = $(this).attr("href")..split('page=')[1];
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
                  //alert(msg);
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
            url: '{{ URL::to('/vendor/picker-status') }}',
        }).done(function( data ) 
        {   
          search();
          if(data.class == 'success')
            {showMsg(data.message, "success");}
          
          
        });
        
    }

    $(document).ready(function() {
        loadPiece( '{{ URL::to('/vendor/vendor-rating') }}');
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
            url: '{{ URL::to('/vendor/picker-remove') }}',
        }).done(function( data ) 
        {   
          search();
           if(data.class == 'success')
            {showMsg(data.message, "success");}
          
          
        });
			   }
    }
</script>
<section class="content-header">
      <h1>Vendor Rating</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/vendor')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Vendor Rating</li>
      </ol>
    </section>
    <section class="" style="padding: 15px;">
      <div class="row">
        <div class="col-xs-12 page-user">
          <div class="box">
            <div class="box-body">
            {!! Form::open(array('url' => '/vendor/vendor-rating', 'method' => 'post','name'=>'mySearchForm','files'=>true,'novalidate' => 'novalidate','id' => 'mySearchForm')) !!}
           
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
            <div class="box-header">
              <h3 class="box-title">Vendor Rating Data Table</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="replace-div">
                 @include('vendor.vendor-rating.search')
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