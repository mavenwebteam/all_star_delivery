@extends('layouts.sub_admin_master')
@section('title')
All Star Delivery- Admin Profile
@stop
@section('content') 
<style>
.alert-danger1{color:#dd4b39;}
.alert{padding:0px !important;}

</style>
<section class="content">
      <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2>Admin <span>Profile</span></h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/sub-admin')}}">Home</a></li>
          <li><span>Profile Management</span></li>
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
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(array('url' => '/sub-admin/edit-profile-post', 'method' => 'post','name'=>'editProfile','files'=>true,'novalidate' => 'novalidate')) !!}
              <div class="box-body">
             
                <div class="form-group">
                  <label for="exampleInputEmail1">First Name</label>
                  {!! Form::text('first_name',$admindata->first_name, ['class' => 'form-control','placeholder' => 'First Name','required'=>'required']) !!}
                  @if ($errors->has('first_name'))
                  <p class="alert alert-danger">{{ $errors->first('first_name') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Last Name</label>
                  {!! Form::text('last_name',$admindata->last_name, ['class' => 'form-control','placeholder' => 'Last Name','required'=>'required']) !!}
                  @if ($errors->has('last_name'))
                  <p class="alert alert-danger">{{ $errors->first('last_name') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Email</label>
                  {!! Form::text('email',$admindata->email, ['class' => 'form-control','placeholder' => 'Email','required'=>'required']) !!}
                  @if ($errors->has('email'))
                  <p class="alert alert-danger">{{ $errors->first('email') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Passsword']) !!}
                  @if ($errors->has('password'))
                  <p class="alert alert-danger">{{ $errors->first('password') }}</p>
                  @endif
                </div>
				 <div class="form-group">
                  <label for="exampleInputPassword1">Confirm Password</label>
                  {!! Form::password('confirm_password', ['class' => 'form-control','placeholder' => 'Confirm Passsword']) !!}
                  @if ($errors->has('confirm_password'))
                  <p class="alert alert-danger">{{ $errors->first('confirm_password') }}</p>
                  @endif
                </div>
				
                     <div class="form-group">
                        <label class=" col-md-1 col-sm-6 col-xs-12 control-label">Profile Image</label>
                        
                                
                                <div class="col-md-11">
                                    <span class="import-excel">
                                    <input type="file" name="profile_pic" id="profile_pic" class="form-control input-file" onkeypress="error_remove()" style="width: 70px;" accept='image/*'>
                                    <button class="btn btn-outline-success">Browse</button>
                                </span>
                                </div>
                          
                    </div>
				@if ($errors->has('profile_pic'))
                  <p class="alert alert-danger" style="margin-top:66px;">{{ $errors->first('profile_pic') }}</p>
                  @endif 
                 @if($admindata->profile_pic!="")
               
                    <div class=" form-group">
                        <label class=" control-label"></label>
                        <img src="{{asset('media/users').'/'.$admindata->profile_pic}}" width="70px" height="70px">
                    </div>
               
                @else
             
                    <div class="form-group">
                        <label class="control-label"></label>
                        <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
                    </div>
              
                @endif
              <!-- /.box-body -->

              <div class="box-footer">
               {!! Form::submit('Update',['class' => 'btn btn-primary']) !!}
               <a href="{{URL::to('/sub-admin')}}">
			    {!! Form::button('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}</a>
              </div>
              {!! Form::close() !!}
          </div>
         
        </div>
       
      </div>
      <!-- /.row -->
    </section>
@stop 