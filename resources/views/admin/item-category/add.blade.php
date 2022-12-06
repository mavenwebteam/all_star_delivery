<script type="text/javascript">



    $(document).ready(function () 



    {  $.LoadingOverlay("hide");



        $( '#addItemCategoryForm' ).on( 'submit', function(e) 



        {  $.LoadingOverlay('show');

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







            type: "POST",



            data:  new FormData(this),



            contentType: false,



            cache: false,



            processData:false,



            url: '{{ URL::to('/admin/add-item-category-post') }}',



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



            {  $.LoadingOverlay('hide'); showMsg(data.message, "success");}



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



    {!! Form::open(array('url' => '/admin/add-item-category-post', 'method' => 'post','name'=>'addItemCategoryForm','files'=>true,'novalidate' => 'novalidate','id' => 'addItemCategoryForm')) !!}



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
                                 {!! Form::select('category_id',$helper->SelectBusinessCategory(),null, ['class' => 'form-control ','required'=>'required','id'=>'category_id','onChange' => 'error_remove()' ]) !!}
                            </div>
                        </div>
                     </div> 

                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Item Category Name (en)<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('name_en',null, ['class' => 'form-control','placeholder' => 'Item Category Name (en)','required'=>'required','id'=>'name_en','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                         </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Item Category Name (burmese)<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('name_burmese',null, ['class' => 'form-control','placeholder' => 'Item Category Name (burmese)','required'=>'required','id'=>'name_burmese','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                         </div>
                    </div>
                    
                     <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Category Image<span class="red-star">*</span></label>
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

<script language="javascript">

$("#styled-checkbox").click(function () {

     $('input:checkbox').not(this).prop('checked', this.checked);

 });

function filterFunction(){ 

		    var input, filter, ul, li, a, i;

		    input = document.getElementById("myInput");

			//alert(input);

		    filter = input.value.toUpperCase();

		    div = document.getElementById("myDropdown");

		    a = div.getElementsByTagName("a");

		    for (i = 0; i < a.length; i++) {

		        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {

		            a[i].style.display = "";

		        } else {

		            a[i].style.display = "none";

		        }

		    }

		}



</script>

