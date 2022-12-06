<script type="text/javascript">

    $(document).ready(function () 

    {  $.LoadingOverlay("hide");

        $( '#editItemCatgeoryForm' ).on( 'submit', function(e) 

        {   $.LoadingOverlay('show');
			
			var checklogin1 = checklogin();
		if(checklogin1  == true){	
            e.preventDefault();

               $.ajaxSetup({

                  headers: {

                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

                  }

              });

           

           

            $.ajax({

                dataType: 'json',

                data:  new FormData(this),

            contentType: false,

            cache: false,

            processData:false,

            type: "POST",

            url: '{{ URL::to('/sub-admin/edit-item-category-post') }}',

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


		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}  
    });

});

</script>







<style>

.users_select{

    border: 1px solid #d2d6de;

    border-top: 0px;

    padding-top: 10px;

    padding-bottom: 10px;

    max-height: 200px;

    overflow-y: scroll;

}

.users_select {
    list-style: none;
}
.users_select a {
    color: #292929 !important;
}

</style>
    {!! Form::open(array('url' => '/sub-admin/edit-item-category-post', 'method' => 'post','name'=>'editItemCatgeoryForm','files'=>true,'novalidate' => 'novalidate','id' => 'editItemCatgeoryForm')) !!}
    <div class="col-sm-12 p-r-30">

        <div class="panel form-horizontal panel-transparent">

            <div class="panel-body">

                <div class="row">

                <div class="alert alert-danger" style="display:none"></div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Business Category<span class="red-star">*</span></label>
                                <div class="col-lg-8 col-sm-6 col-xs-12">
                                    <?php $helper=new App\Helpers;?>
                                    {!! Form::select('category_id',$helper->SelectBusinessCategory(),$catdata->category_id, ['class' => 'form-control ','required'=>'required','id'=>'category_id','onChange' => 'error_remove()' ]) !!}
                                </div>
                            </div>
                     </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Item Category Name (en)<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::hidden('id',base64_encode($catdata->id),['id'=>'id']) !!}

                            {!! Form::text('name_en',$catdata->name_en, ['class' => 'form-control','placeholder' => 'Item Category Name (en)','required'=>'required','id'=>'name_en','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                            

                        </div>

                    </div>

                    <div class="col-12 col-sm-12 col-md-6">

                    <div class="row form-group">

                        <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Item Category Name (burmese)<span class="red-star">*</span></label>

                        <div class="col-lg-8 col-sm-6 col-xs-12">

                        {!! Form::text('name_burmese',$catdata->name_burmese, ['class' => 'form-control','placeholder' => 'Item Category Name (burmese)','required'=>'required','id'=>'name_burmese','onkeypress' => 'error_remove()' ]) !!}

                        </div>

                        

                    </div>

                    </div>

                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Category Image<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::file('image', ['class' => 'form-control','id'=>'image','accept'=>'image/*' ]) !!}

							@if($catdata->image!="") <img src="{{asset('media/item_category/'.$catdata->image)}}" style="width:50px; padding-top:20px;"> @else No Image @endif

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

