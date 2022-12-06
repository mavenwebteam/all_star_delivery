<script type="text/javascript">


    $(document).ready(function () 


    {  $.LoadingOverlay("hide");


        $( '#addBusinessCategoryForm' ).on( 'submit', function(e) 


        { 
		
		$.LoadingOverlay('show');

		var checklogin1 = checklogin();
		if(checklogin1  == true){	
            e.preventDefault();


               $.ajaxSetup({


                  headers: {


                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')


                  }


              });


          


           // var name = $('#name').val();


           


            $.ajax({
           
		    dataType: 'json',
            type: "POST",

            data:  new FormData(this),

            contentType: false,

            cache: false,

            processData:false,

            url: '{{ URL::to('/admin/add-business-category-post') }}',


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


            { $.LoadingOverlay('hide'); showMsg(data.message, "success");}


            $("#myModal").modal('hide');


            


	            return false;


          }
      $.LoadingOverlay('hide');

            


                         


        });



}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  

    });
		

});


</script>





    {!! Form::open(array('url' => '/admin/add-business-category-post', 'method' => 'post','name'=>'addBusinessCategoryForm','files'=>true,'novalidate' => 'novalidate','id' => 'addBusinessCategoryForm')) !!}


    <div class="col-sm-12 p-r-30">


        <div class="panel form-horizontal panel-transparent">


            <div class="panel-body">


                <div class="row">


                <div class="alert alert-danger" style="display:none"></div>


                
                   


                    <div class="col-12 col-sm-12 col-md-6">


                        <div class="row form-group">


                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Name (en)<span class="red-star">*</span></label>


                            <div class="col-lg-8 col-sm-6 col-xs-12">


                            {!! Form::text('name_en',null, ['class' => 'form-control','placeholder' => 'Name (en)','required'=>'required','id'=>'name_en','onkeypress' => 'error_remove()' ]) !!}


                            </div>


                            


                        </div>


                    </div>

                    <div class="col-12 col-sm-12 col-md-6">


                    <div class="row form-group">


                        <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Name (Burmese)<span class="red-star">*</span></label>


                        <div class="col-lg-8 col-sm-6 col-xs-12">


                        {!! Form::text('name_burmese',null, ['class' => 'form-control','placeholder' => 'Name (Burmese)','required'=>'required','id'=>'name_burmese','onkeypress' => 'error_remove()' ]) !!}


                        </div>


                        


                    </div>


                    </div>

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label"> Image<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::file('image', ['class' => 'form-control','id'=>'image','accept'=>'image/*' ]) !!}

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


