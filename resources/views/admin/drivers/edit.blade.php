<script type="text/javascript">

$(document).ready(function () 
    {   
        $.LoadingOverlay("hide");
        $( '#addUserForm' ).on( 'submit', function(e) 
        {   
            $.LoadingOverlay("show");		
            var checklogin1 = checklogin();		
            if(checklogin1  == true)
            {	
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: '{{ route("admin.drivers.update",$data->id) }}',
                }).done(function( data ) 
                {  
                    error_remove ();
                    if(data.success==false)
                    {
                        $.each(data.errors, function(key, value) {
                            $('#' + key).parent().addClass('form-group has-error');
                            $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('#' + key));
                        });
                    }else{
                        searchItem();
                        if(data.class == 'success')
                        {     
                            $.LoadingOverlay("hide");  
                            showMsg(data.message, "success");
                        }
                        $("#myModal").modal('hide');
                        return false;
                    }
                    $.LoadingOverlay("hide");     
                });
            }
            else
            {
                location.reload();
                $.LoadingOverlay("hide");		
            }  
        });
        checkVhicleType();
    });


function fileInputChange(aFiles) {

    var newFiles = []; var files = [];
    for (var index = 0; index < aFiles.length; index++) {
        var file = aFiles[index];
        var re = /(?:\.([^.]+))?$/;
        ext = re.exec(file.name)[1];

        if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'PNG'|| ext == 'JPG'|| ext == 'JPEG') {

            newFiles.push(file);
            files.push(file);
        } else {
            alert("Please Upload jpg, jpeg, png formats only.");
            $('html, body').animate({scrollTop: 0}, 1000);
        }
    }

    newFiles.forEach(function (file) {
        var reader = new FileReader();
        reader.addEventListener("load", function () {
            var ImgElement = $('<div></div>')
                .addClass('choose_img').append($('<img />').attr("src", this.result)).append($('<span></span>').addClass("close").append($("<i></i>").addClass("fa fa-times")));
            $(".no_image").hide();
            $('.all_img_outer').append(ImgElement);

        }, false);

        reader.readAsDataURL(file);
    });
}

// -----hide bike field on change vhicle type start ------
function checkVhicleType(){
    let vehicleType = $('input[name="vehicle_type"]:checked').val();
    if(vehicleType == 'Motorbike'){
        $("#motorbike").show();
    }else{
        $("#motorbike").hide();
    }
}
$('input[name="vehicle_type"]').on('change', function(){
    checkVhicleType();
});
// -----hide bike field on change vhicle type end ------



</script>

    {!! Form::open(array('url' => 'admin/drivers'.$data->id, 'method' => 'post','name'=>'addUserForm','files' =>'true','novalidate' => 'novalidate','id' => 'addUserForm')) !!}
    @method('PUT')    
        <div class="col-sm-12 p-r-30">
            <div class="panel panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="col-xs-6">
                    <label class="first_name">First Name<span class="red-star">*</span></label>
                    {!! Form::text('first_name',$data->first_name, ['class' => 'form-control','placeholder' => 'First Name','required'=>'required','id'=>'first_name','onkeypress' => 'error_remove()' ]) !!}
                </div>
                <div class="col-xs-6">
                    <label for="last_name">Last Name<span class="red-star">*</span></label>
                    {!! Form::text('last_name',$data->last_name, ['class' => 'form-control','placeholder' => 'Last Name','required'=>'required','id'=>'last_name','onkeypress' => 'error_remove()' ]) !!}
                </div>
                </div>
                <br>
                <div class="row">
                <div class="col-xs-6">
                    <label for="email">Email<span class="red-star">*</span></label>
                    {!! Form::text('email',$data->email, ['class' => 'form-control','placeholder' => 'email','required'=>'required','id'=>'email','onkeypress' => 'error_remove()']) !!}
                </div>
                <div class="col-xs-6">
                    <label for="mobile">Mobile<span class="red-star">*</span></label>
                    {!! Form::text('mobile',$data->mobile, ['class' => 'form-control','placeholder' => 'Mobile','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()', 'onkeypress' => "return isNumberKey(event)"]) !!}
                </div>
                </div> 
                <br>
                <div class="row">
                
                <div class="col-xs-6">
                    <label for="profile_pic">Profile Picture</label>
                    {!! Form::file('profile_pic',['class' => 'form-control','placeholder' => '','accept'=>'image/*','required'=>'required','id'=>'profile_pic','onkeypress' => 'error_remove()']) !!}
                </div>
                @if($data->profile_pic && file_exists(public_path().'/media/users/thumb/'.$data->profile_pic))
                    <div class="col-xs-2" style="padding-top: 16px;">
                        <img src="{{ asset('media/users/thumb/'.$data->profile_pic) }}" alt="" height="50" width="50"/>
                    </div>
                @endif
                </div>  
                <br>
                <h3>Vehicle Detail</h3>
                <div class="row">
                <div class="col-xs-12">
                    <label for="vehicle_type">Vehicle Type<span class="red-star">*</span></label>
                    <div class="form-group">
                    <label>
                        <input type="radio" value="Bicycle" name="vehicle_type" class="flat-red" id="typeBicycle" 
                        @if($data->vehicle && $data->vehicle->vehicle_type=='Bicycle') checked @endif>
                        Bicycle
                    </label>
                    <label>
                        <input type="radio" value="Motorbike" name="vehicle_type" class="flat-red" id="typeMotorbike"
                        @if($data->vehicle && $data->vehicle->vehicle_type=='Motorbike') checked @endif>
                        Motorbike
                    </label>
                    </div>
                </div>
                </div>
                <div id="motorbike">  
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                        <label for="model">Vehicle Model</label>                   
                        {!! Form::text('model', object_get($data,'vehicle.model', ''), ['class' => 'form-control','placeholder' => 'model','required'=>'required','id'=>'model','onkeypress' => 'error_remove()']) !!}
                        </div>
                        <div class="col-xs-6">
                        <label for="brand_name">Brand Name</label>
                        {!! Form::text('brand_name', object_get($data,'vehicle.brand_name', ''), ['class' => 'form-control','placeholder' => 'Brand Name','required'=>'required','id'=>'brand_name','onkeypress' => 'error_remove()']) !!}
                        </div>
                    </div> 
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                        <label for="year">Year</label>
                        {!! Form::text('year', object_get($data,'vehicle.year', ''), ['class' => 'form-control','placeholder' => 'Year','required'=>'required','id'=>'year','onkeypress' => 'error_remove()', 'onkeypress' => "return isNumberKey(event)"]) !!}
                        </div>
                        <div class="col-xs-6">
                        <label for="vehicle_num">Vehicle Number</label>
                        {!! Form::text('vehicle_num', object_get($data,'vehicle.vehicle_num', ''), ['class' => 'form-control','placeholder' => 'Vehicle Number','required'=>'required','id'=>'vehicle_num','onkeypress' => 'error_remove()']) !!}
                        </div>
                    </div> 
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                        <label for="vehicle_num_img">Vehicle Number Image</label>
                        {!! Form::file('vehicle_num_img', ['class' => 'form-control','placeholder' => '','required'=>'required','id'=>'vehicle_num_img','accept'=>'image/*','onkeypress' => 'error_remove()']) !!}
                        </div>
                        @if(object_get($data,'vehicle.vehicle_num_img',NULL) && file_exists(public_path().'/media/vehicle/'.$data->vehicle->vehicle_num_img))
                        <div class="col-xs-2" style="padding-top: 16px;">
                            <img src="{{ asset('media/vehicle/'.$data->vehicle->vehicle_num_img) }}" alt="" height="50" width="50"/>
                        </div>
                        @endif
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                        <label for="licence_img">Licence Image</label>
                        {!! Form::file('licence_img',['class' => 'form-control','placeholder' => 'licence_img','required'=>'required','id'=>'licence_img','accept'=>'image/*','onkeypress' => 'error_remove()', 'accept'=>'image/*', 'onkeypress' => "return isNumberKey(event)"]) !!}
                        </div>
                        @if(object_get($data, 'vehicle.licence_img', NULL) && file_exists(public_path().'/media/vehicle/'.$data->vehicle->licence_img))
                            <div class="col-xs-2" style="padding-top: 16px;">
                                <img src="{{ asset('media/vehicle/'.$data->vehicle->licence_img) }}" alt="" height="50" width="50"/>
                            </div>
                        @endif
                    </div> 
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                            <label for="licence_num">Licence Number</label>
                            {!! Form::text('licence_num',object_get($data,'vehicle.licence_num', ''), ['class' => 'form-control','placeholder' => 'Licence Number','required'=>'required','id'=>'licence_num','onkeypress' => 'error_remove()']) !!}
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="row form-btn text-center">
            <div class="col-sm-12 p-r-30">
            <div class="col-md-12">
                {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
                {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
            </div>
            </div>
        </div>
    {!! Form::close() !!}