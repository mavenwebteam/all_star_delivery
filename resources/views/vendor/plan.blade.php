@extends('layouts.vendormaster')
@section('title')
BRINGOO- Plan
@stop
@section('content') 

<section class="content">
      <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2> Plan</h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/vendor')}}">Home</a></li>
          <li><span>Plan Management</span></li>
        </ul>
      </div>
</div>


<style>
.border h3 {
    border: 1px solid #f4f4f4;
    margin-bottom: 0px;
    border-bottom: 0px;
    padding: 15px;
    border-radius: 5px 5px 0px 0px;
	text-align:center;
}
.tableOuterBox {
    padding: 0px 15px;
    width: 80%;
    margin: 30px auto;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.border th {
    text-align: center;
}

</style>
@if(Session::has('msg')) {!! session('msg') !!} @endif
        <!-- left column -->
        <div class="col-md-12">
          
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Plan</h3>
            </div> 
			<div class="tableOuterBox">
				<div class="row">
					<div class="col-md-6">
						<div class="border">
							<h3>Free Plan</h3>
							<table class="table table-bordered table-hover">
								<tbody>
									<tr> <th>Picker manager</th>
									</tr>
									<tr> <th>Outlet Manager </th></tr>
									<tr> <th>Store Profile</th></tr>
							
									<tr> <th>Vendor Profile</th></tr>
									<tr> <th>Delivery Boy Manager</th></tr>
								</tbody>
							</table>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="border">
							<h3>Purchase Plan</h3>
							<table class="table table-bordered table-hover">
								<tbody>
									<tr> <th>product manager</th>
									</tr>
									<tr> <th>Product Inventory Manager </th></tr>
									<tr> <th>Coupon Code Manager</th></tr>
							
									<tr> <th>Earning Manager</th></tr>
									<tr> <th>Order Manager </th></tr>
									<tr> <th>Rating & Review Manager </th></tr>
									<tr> <th>Plan price : $ <?php echo Config::get("Site.vendor_plan_price"); ?></th></tr>
									<tr> <th>  <a href="{{URL::to('/vendor/purchase-plan-post')}}">{!! Form::submit('Purchase plan',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!} </a></th></tr>
								</tbody>
							</table>
						</div>	
					</div>
				</div>
			</div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
             
                
          </div>
         
        </div>
       
      </div>
      <!-- /.row -->
    </section>
@stop 