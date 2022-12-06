<script type="text/javascript">

    $(document).ready(function () 

    {   $.LoadingOverlay('hide');

        $( '#addUserForm' ).on( 'submit', function(e) 

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

            data:  new FormData(this),

            contentType: false,

            cache: false,

            processData:false,

            url: '{{ URL::to('/admin/add-vendor-post') }}',

        }).done(function( data ) 

        {  error_remove (); if(data.success==false)

            {

                $.each(data.errors, function(key, value){

                    $('#'+key).closest('.form-group').addClass('has-error');

					$('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));

                });



          }else{

            search();

            if(data.class == 'success')

            {showMsg(data.message, "success");}

            $("#myModal").modal('hide');

            

	            return false;

          }

            

                         

        });



    });

});

</script>



    {!! Form::open(array('url' => '/admin/add-vendor-post', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}

    <div class="col-sm-12 p-r-30">

        <div class="panel form-horizontal panel-transparent">

            <div class="panel-body">

                <div class="row">

                <div class="alert alert-danger" style="display:none"></div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="row form-group">
                        <label class="col-md-4 col-sm-6 col-xs-12 control-label">Image</label>
                       
                                <div class="col-md-8">
                                    <span class="import-excel">
                                    <input type="file" name="image" id="technician_category_image" class="form-control input-file" onkeypress="error_remove()">
                                    <button class="btn btn-outline-success">Browse</button>
                                </span>
                                </div>
                           
                    </div>
                </div>
              <!--  <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Type<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            <?php $helper=new App\Helpers;?>

                            {!! Form::select('type',$helper->SelectUserType(),null, ['class' => 'form-control','required'=>'required','id'=>'type','onkeypress' => 'error_remove()']) !!}

                            </div>

                            

                        </div>

                    </div>-->

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">First Name<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('first_name',null, ['class' => 'form-control','placeholder' => 'First Name','required'=>'required','id'=>'first_name','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                            

                        </div>

                    </div>

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Last Name<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('last_name',null, ['class' => 'form-control','placeholder' => 'Last Name','required'=>'required','id'=>'last_name','onkeypress' => 'error_remove()' ]) !!}   

                            </div>

                        </div>

                    </div>

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Email<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('email',null, ['class' => 'form-control','placeholder' => 'Email','required'=>'required','id'=>'email','onkeypress' => 'error_remove()' ]) !!}   

                            </div>

                        </div>

                    </div>

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Mobile<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            <div class="row">

                                <div class="col-lg-4 col-sm-6 col-xs-12">

                                {!! Form::number('country_code',null, ['class' => 'form-control','placeholder' => 'Code','id'=>'country_code','onkeypress' => 'error_remove()' ]) !!}   

                                </div>

                                <div class="col-lg-8 col-sm-6 col-xs-12">

                                {!! Form::number('mobile',null, ['class' => 'form-control','placeholder' => 'Mobile ','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()' ]) !!}   

                                </div>

                            </div>

                        </div>

                       

                           

                        </div>

                    </div>

                    

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Password<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Password ','required'=>'required','id'=>'password','onkeypress' => 'error_remove()' ]) !!}   

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

        {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}

        {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}

        </div>

    </div>

    {!! Form::close() !!}

