@extends('layouts.adminmaster')
@section('title')
BRINGOO- Setting Manager
@stop
@section('content') 
<style>
.alert-danger1{color:#dd4b39;}
.alert{padding:0px !important;}

</style>
<section class="content">
      <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2>Admin <span>Setting</span></h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/admin')}}">Home</a></li>
          <li><span>Setting Management</span></li>
        </ul>
      </div>
</div>

<div class="row">
      <div class="col-md-12">
         @if(Session::has('msg')) {!! session('msg') !!} @endif
      </div>
</div>

        <!-- left column -->
        <div class="col-md-12">
          
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Setting</h3>
            </div> 
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(array('url' => '/admin/setting', 'method' => 'post','name'=>'editProfile','files'=>true,'novalidate' => 'novalidate')) !!}
              <div class="box-body">
              <div class="alert alert-danger" style="display:none"></div>
                <div class="row">
               
                   <!--<div class="col-12 col-sm-12 col-md-6">
                   
                <div class="form-group">
                  <label for="exampleInputEmail1">Petrol Cost</label>
                  {!! Form::text('petrol_cost',$settingdata->petrol_cost, ['class' => 'form-control','placeholder' => 'Petrol Cost','required'=>'required']) !!}
                  @if ($errors->has('petrol_cost'))
                  <p class="alert alert-danger">{{ $errors->first('petrol_cost') }}</p>
                  @endif
                </div>
				</div>-->
				 <div class="col-12 col-sm-12 col-md-6">
                   
                <div class="form-group">
                  <label for="exampleInputEmail1">Cash Order Max limit for customer</label>
                  {!! Form::text('max_cash_order_limi',$settingdata->max_cash_order_limi, ['class' => 'form-control','placeholder' => 'Cash Order Max limit','required'=>'required']) !!}
                  @if ($errors->has('max_cash_order_limi'))
                  <p class="alert alert-danger1">{{ $errors->first('max_cash_order_limi') }}</p>
                  @endif
                </div>
				</div>
				<div class="col-12 col-sm-12 col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Order Cancel Duration (In Hours)</label>
                  {!! Form::text('order_cancel_duration',$settingdata->order_cancel_duration, ['class' => 'form-control','placeholder' => 'Order Cancel Duration (In Hours)','required'=>'required']) !!}
                  @if ($errors->has('order_cancel_duration'))
                  <p class="alert alert-danger1">{{ $errors->first('order_cancel_duration') }}</p>
                  @endif
                </div>
				</div>
				</div>
				
				 <div class="row">
				 <div class="col-12 col-sm-12 col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Minimum Order value</label>
                  {!! Form::text('min_order_value',$settingdata->min_order_value, ['class' => 'form-control','placeholder' => 'Minimum Order value','required'=>'required']) !!}
                  @if ($errors->has('min_order_value'))
                  <p class="alert alert-danger1">{{ $errors->first('min_order_value') }}</p>
                  @endif
                </div>
				</div>
				<div class="col-12 col-sm-12 col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Admin commission </label>
                  {!! Form::text('admin_commission',$settingdata->admin_commission, ['class' => 'form-control','placeholder' => 'Admin commission','required'=>'required']) !!}
                  @if ($errors->has('admin_commission'))
                  <p class="alert alert-danger1">{{ $errors->first('admin_commission') }}</p>
                  @endif
                </div>
				</div>
				 
				 </div>

				 <div class="row">
                   <h4 style="padding-left:20px;">Delivery Settings</h4>
                   <div class="col-12 col-sm-12 col-md-6">
					<div class="form-group">
                  <label for="exampleInputEmail1">Delivery Start Time</label>
				  
				  
				  

                  {!! Form::text('delivery_start_time',$settingdata->delivery_start_time, ['class' => 'form-control timepicker','placeholder' => 'Delivery Start Time','required'=>'required','id'=>'delivery_start_time']) !!}
                  @if ($errors->has('delivery_start_time'))
                  <p class="alert alert-danger1">{{ $errors->first('delivery_start_time') }}</p>
                  @endif
                </div>
				   </div>
				   <div class="col-12 col-sm-12 col-md-6">
				         <div class="form-group">
                  <label for="exampleInputEmail1">Delivery End Time</label>
                  {!! Form::text('delivery_end_time',$settingdata->delivery_end_time, ['class' => 'form-control','placeholder' => 'Delivery End Time','required'=>'required','id'=>'delivery_end_time']) !!}
                  @if ($errors->has('delivery_end_time'))
                  <p class="alert alert-danger1">{{ $errors->first('delivery_end_time') }}</p>
                  @endif
                </div>
				   </div>
				 </div>  
				  <div class="row">
                 
                   <div class="col-12 col-sm-12 col-md-6">
					<div class="form-group">
                  <label for="exampleInputEmail1">Delivery Slots</label>
                  {!! Form::text('delivery_slots',$settingdata->delivery_slots, ['class' => 'form-control','placeholder' => 'Delivery Slots','required'=>'required']) !!}
                  @if ($errors->has('delivery_slots'))
                  <p class="alert alert-danger1">{{ $errors->first('delivery_slots') }}</p>
                  @endif
                </div>
				   </div>
				   <div class="col-12 col-sm-12 col-md-6">
				         <div class="form-group">
                  <label for="exampleInputEmail1">Delivery Slots Duration</label>
                  {!! Form::text('delivery_slot_duration',$settingdata->delivery_slot_duration, ['class' => 'form-control','placeholder' => 'Delivery Slots Duration','required'=>'required']) !!}
                  @if ($errors->has('delivery_slot_duration'))
                  <p class="alert alert-danger1">{{ $errors->first('delivery_slot_duration') }}</p>
                  @endif
                </div>
				   </div>
				 </div>  
				 
				 
				   <div class="row">
                 
                   <div class="col-12 col-sm-12 col-md-6">
					<div class="form-group">
                  <label for="exampleInputEmail1">Invoice Note</label>
                  {!! Form::textarea('invoice_note',$settingdata->invoice_note, ['class' => 'form-control','placeholder' => 'Invoice Note','required'=>'required']) !!}
                  @if ($errors->has('invoice_note'))
                  <p class="alert alert-danger1">{{ $errors->first('invoice_note') }}</p>
                  @endif
                </div>
				   </div>
				   </div>
				    <!-- <div class="row">
					    <div class="col-12 col-sm-12 col-md-6">
					  @if($settingdata->all_category_img!="")
              
                    <div class="row form-group">
                        <label class="col-lg-2 col-sm-6 col-xs-12 control-label"></label>
                        <img src="{{asset('/media/category').'/'.$settingdata->all_category_img}}" width="70px" height="70px">
                    </div>
                
                @else
               
                    <div class="row form-group">
                        <label class="col-lg-2 col-sm-6 col-xs-12 control-label"><span class="red-star">*</span></label>
                        <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
                    </div>
              
                @endif
				  
				         <div class="form-group">
                  <label for="exampleInputEmail1">All Category Image</label>
                
				   <span class="import-excel">
                                    <input type="file" name="all_category_img" id="technician_category_imageqq" class="form-control " onkeypress="error_remove()">
                                 
                                </span>
				  
                  @if ($errors->has('all_category_img'))
                  <p class="alert alert-danger">{{ $errors->first('all_category_img') }}</p>
                  @endif
                </div>
				   </div>
				 </div>  -->
				 
				 
				 
              <!-- /.box-body -->

              <div class="box-footer">
               {!! Form::submit('Update',['class' => 'btn btn-primary']) !!}
               <a href="{{URL::to('/admin')}}">Cancel</a>
              </div>
              {!! Form::close() !!}
          </div>
         
        </div>
       
      </div>
      <!-- /.row -->
	
    </section>
@stop 
