<script type="text/javascript">
    $(document).ready(function () 
    {   $.LoadingOverlay('hide');
        $( '#editUserForm' ).on( 'submit', function(e) 
        {   $.LoadingOverlay('show');			var checklogin1 = checklogin();		if(checklogin1  == true){	
            e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
           
            $.ajax({
                dataType: 'json',

                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,         
            url: '{{ URL::to('/sub-admin/edit-user-post') }}',
        }).done(function( data ) 
        {  error_remove (); if(data.success==false)
            {
                $.each(data.errors, function(key, value){
                    $('#'+key).closest('.form-group').addClass('has-error');
					$('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));
                });

          }else{
            search();
            //alert(data.message);
            if(data.class == 'success')
            {$.LoadingOverlay('hide'); showMsg(data.message, "success");}
            $("#myModal").modal('hide');
            
	            return false;
          }
            $.LoadingOverlay('hide');
                         
        });
								}else{			location.reload();				$.LoadingOverlay("hide");		}  
    });
});
</script>

    {!! Form::open(array('url' => '/sub-admin/edit-user-post', 'method' => 'post','name'=>'editUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'editUserForm')) !!}
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="row form-group">
                        <label class=" col-md-4 col-sm-6 col-xs-12 control-label">Image</label>
                        
                                
                                <div class="col-md-8">
                                     {!! Form::file('profile_pic', ['class' => 'form-control','id'=>'profile_pic','accept'=>'image/*' ]) !!}
                                </div>
                            
                    </div>
                </div>
                @if($userdata->profile_pic!="")
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="row form-group">
                        <label class="col-md-4 col-sm-6 col-xs-12 control-label"></label>
						<div class="col-md-8">
                        <img src="{{asset('/media/users').'/'.$userdata->profile_pic}}" width="70px" height="70px">
						</div>
                    </div>
                </div>
                @else
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="row form-group">
                      <label class="col-md-4 col-sm-6 col-xs-12 control-label"></label>
					  <div class="col-md-8">
                        <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
						</div>
                    </div>
                </div>
                @endif
                   {!! Form::hidden('user_id',base64_encode($userdata->id),['id'=>'user_id']) !!}
				    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">First Name<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('first_name1',$userdata->first_name, ['class' => 'form-control','placeholder' => 'First Name','required'=>'required','id'=>'first_name1','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Last Name<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('last_name',$userdata->last_name, ['class' => 'form-control','placeholder' => 'Last Name','required'=>'required','id'=>'last_name','onkeypress' => 'error_remove()' ]) !!}   
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Email<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                                <input value="{{ $userdata->email }}" type="text" name="email" id="email" class="form-control" autocomplete="off" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Password</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Password ','required'=>'required','id'=>'password','autocomplete'=>'off','onkeypress' => 'error_remove()' ]) !!}   
                            </div>
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Confirm Password</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::password('confirm_password', ['class' => 'form-control','placeholder' => 'Confirm Password ','required'=>'required','id'=>'confirm_password','onkeypress' => 'error_remove()', 'autocomplete'=>'off' ]) !!}   
                            </div>
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Address<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                          
                            {!! Form::text('address',$userdata->address, ['class' => 'form-control address','placeholder' => 'Address ','required'=>'required','id'=>'address','onkeypress' => 'error_remove()' ]) !!}
							
							<input type="hidden" name="latitude" id="latitude" value="{{$userdata->latitude}}"/>
								<input type="hidden" name="longitude" id="longitude" value="{{$userdata->longitude}}"/>
							
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Mobile<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-xs-12">
								 {!! Form::select('country_code',$countrycode_box,'+'.$userdata->country_code, ['class' => 'form-control','required'=>'required','id'=>'country_code','onkeypress' => 'error_remove()']) !!}
                                 
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                {!! Form::text('mobile',$userdata->mobile, ['class' => 'form-control','placeholder' => 'Mobile ','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()' ]) !!}   
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-btn text-center">
        <div class="col-sm-12 p-r-30">
        <div class="col-md-12"> 
        {!! Form::submit('Update',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
        {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
        </div>
    </div>
    {!! Form::close() !!}
