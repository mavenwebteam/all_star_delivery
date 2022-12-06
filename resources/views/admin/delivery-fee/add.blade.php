<script type = "text/javascript" >
$(document).ready(function()
{
   $.LoadingOverlay('hide');
   $('#addFeeForm').on('submit', function(e)
   {
      $.LoadingOverlay('show');
      var checklogin1 = checklogin();
      if (checklogin1 == true) {
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
         url: '{{ URL::to('/admin/delivery-fee-store') }}',
         }).done(function(data)
         {
             console.log(data);
            error_remove();
            if (data.success == false)
            {
               $.each(data.errors, function(key, value) {
                  $('#' + key).closest('.form-group').addClass('has-error');
                  $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('#' + key));
               });
            } else {
               if (data.class == 'success')
               {
                  $.LoadingOverlay('hide');
                  showMsg(data.message, "success");
               }
               $("#myModal").modal('hide');
               location.reload();
               return false;
            }
            $.LoadingOverlay('hide');
         });
   } else {
      location.reload();
      $.LoadingOverlay("hide");
   }
   });
});
</script>
 {!! Form::open(array('url' => '/admin/delivery-fee-store', 'method' => 'post','name'=>'editUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addFeeForm')) !!}
 <div class="col-sm-12 p-r-30">
     <div class="panel form-horizontal panel-transparent">
         <div class="panel-body">
             <div class="row">
             <div class="alert alert-danger" style="display:none"></div>
                 <div class="col-12 col-sm-12 col-md-6">
                     <div class="row form-group">
                         <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Minimum Distance<span class="red-star">*</span></label>
                         <div class="col-lg-8 col-sm-6 col-xs-12">
                         {!! Form::text('min_distance',null, ['class' => 'form-control','placeholder' => 'Minimum Distance','required'=>'required','id'=>'min_distance','onkeypress' => 'error_remove()' ]) !!}
                         </div>
                     </div>
                 </div>
                 <div class="col-12 col-sm-12 col-md-6">
                     <div class="row form-group">
                         <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Maximum Distance<span class="red-star">*</span></label>
                         <div class="col-lg-8 col-sm-6 col-xs-12">
                         {!! Form::text('max_distance',null, ['class' => 'form-control','placeholder' => 'Last Name','required'=>'required','id'=>'max_distance','onkeypress' => 'error_remove()' ]) !!}   
                         </div>
                     </div>
                 </div>
                 <div class="col-12 col-sm-12 col-md-6">
                     <div class="row form-group">
                         <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Fee<span class="red-star">*</span></label>
                         <div class="col-lg-8 col-sm-6 col-xs-12">
                         {!! Form::text('fee',null, ['class' => 'form-control','placeholder' => 'Fee','required'=>'required','id'=>'fee','onkeypress' => 'error_remove()' ]) !!}   
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
     {!! Form::submit('Submit',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
     {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
     </div>
 </div>
 {!! Form::close() !!}