@extends('layouts.adminmaster')
@section('title')
BRINGOO- Setting Manager
@stop
@section('content') 
<style>
.alert-danger1,.error-message{color:#dd4b39;}
.alert{padding:0px !important;}

</style>
<section class="content">
      <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2>Admin <span>Site Setting</span></h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/admin')}}">Home</a></li>
          <li><span>Site Setting Management</span></li>
        </ul>
      </div>
</div>



        <!-- left column -->
        <div class="col-md-12">
          <div class="row">
      <div class="col-md-12">
	 
         @if(Session::has('msg')) {!! session('msg') !!} @endif
      </div>
</div>
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Setting</h3>
            </div> 
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(array('url' => '/admin/site-setting/Site', 'method' => 'post','name'=>'settingsForm','id'=>'settingsForm','files'=>true,'novalidate' => 'novalidate','class'=>'mws-form')) !!}
              <div class="box-body">
              <div class="alert alert-danger" style="display:none"></div>
                <div class="row">
				 <div class="col-12 col-sm-12 col-md-6">
               <?php 
				if(!empty($result)){
					 $i = 0;
						$half = floor(count($result)/2);
					foreach ($result AS $setting) {
						$text_extention 	= 	'';
						$key				= 	$setting['key'];
						$keyE 				= 	explode('.', $key);
						$keyTitle 			= 	$keyE['1'];
				
						$label = $keyTitle;
						if ($setting['title'] != null) {
							$label = $setting['title'];
						}

						$inputType = 'text';
						if ($setting['input_type'] != null) {
							$inputType = $setting['input_type'];
						} ?>
						
						{{ Form::hidden("Setting[$i]['type']",$inputType) }}
						{{ Form::hidden("Setting[$i]['id']",$setting['id']) }}
						{{ Form::hidden("Setting[$i]['key']",$setting['key']) }}
						<?php 
							
							switch($inputType){
								case 'checkbox':
						?>	
				<div class="form-group">
							<label class="mws-form-label" style="width:300px;"><?php echo $label; ?></label>
							<div class="mws-form-item clearfix">
								<ul class="mws-form-list inline">
									<?php 	
										$checked = ($setting['value'] == 1 )? true: false;
										$val	 = (!empty($setting['value'])) ? $setting['value'] : 0;
									?>
									{{ Form::checkbox("Setting[$i]['value']",$val,$checked) }} 
								</ul>
							</div>
						</div>
						
						<?php
								break;	
								
								case 'text':
								
						?>
						
						<?php if($key == "Site.per_page_product" || $key == 'Site.contact_number' || $key == 'Site.vat'|| $key == 'Site.bonus' || $key == 'Site.delivery_charge'){?>
						<div class="form-group">
							<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
							{{ Form::{$inputType}("Setting[$i]['value']",$setting['value'], ['class' => 'form-control valid','id'=>$key,'onkeypress'=>"javascript:return isNumber(event)", 'required'=>'true']) }} 
							<div class="error-message help-inline"></div>
						</div>
						<?php }else{ ?>
						<div class="form-group">
							<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
							{{ Form::{$inputType}("Setting[$i]['value']",$setting['value'], ['class' => 'form-control valid','id'=>$key,'required'=>'true']) }} 
							<div class="error-message help-inline"></div>
						</div>
						<?php } ?>
						<?php
							break;	
							case 'textarea':	
						?>
						
						<div class="form-group">
							<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
							{{ Form::textarea("Setting[$i]['value']",$setting['value'], ['class' => 'form-control textarea_resize',"rows"=>3,"cols"=>3,'required'=>'true']) }} 
						</div>
						<?php	
							break;
								
						}
						if($i == $half) echo '</div><div class="col-md-6">';
						$i++;
							
					}
				}
			?>	
			</div> 
		</div>
		<div class="mws-button-row">
			<input type="button" onclick="submit_form();" value="Save" class="btn btn-primary">
			
			
		</div>
</div>
</section>
{{ Form::close() }} 
<script type="text/javascript">
function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
    }    

	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	var empty_msg				=	'This field is required';
	var numuric_empty_msg		=	'This field is allow only numuric value';
	var image_validation		=	'Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg';
	var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'];
	function submit_form() { 
		var $inputs = $('.mws-form :input.valid');
		var error  =	0;
		$inputs.each(function() { 
			if($(this).val().trim() == '' ){
				$(this).next().html(empty_msg);
				error	=	1;
			}else {
				if($(this).attr('id') == 'Site.email' ){
					if(!isEmail($(this).val().trim())) { 
						$(this).next().html("Please enter a valid email");
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else if($(this).attr('id') == 'Reading.records_per_page' ){
					if(!$.isNumeric($(this).val().trim())){
						$(this).next().html(numuric_empty_msg);
						error	=	1;
					}else {
						$(this).next().html("");
					}
				}else {
					$(this).next().html("");
				}
			}
		});
		if(error == 0){
			$('.mws-form').submit();
		}
	}
	$('#settingsForm').each(function() {
		$(this).find('input').keypress(function(e) {
           if(e.which == 10 || e.which == 13) {
				submit_form();
				return false;
            }
        });
	});
</script>
@stop 
