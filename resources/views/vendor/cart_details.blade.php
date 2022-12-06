@extends('layouts.vendormaster')
@section('title')
BRINGOO- Cart Details
@stop
@section('content') 

 <link rel="stylesheet" href="https://static.heidelpay.com/v1/heidelpay.css" />
<script type="text/javascript" src="https://static.heidelpay.com/v1/heidelpay.js"></script>
<section class="content">
      <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2> Cart Details</h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/vendor')}}">Home</a></li>
          <li><span>Cart Details Management</span></li>
        </ul>
      </div>
</div>


<style>.heidelpayUI.form {
    position: relative;
    max-width: 100%;
    width: 80%;
    margin: 0 auto;
    border: 1px solid #d2d6de;
    padding: 35px;
    border-radius: 5px;
}
.box{
	border:0px;
}
</style>
@if(Session::has('msg')) {!! session('msg') !!} @endif
        <!-- left column -->
        <div class="col-md-12">
          
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header Cart Details-border">
              <h3 class="box-title">Cart Details</h3>
            </div> 
			<div class="row">
				<div class="col-md-12">
						
			{!! Form::open(array('url' => '/users/payment', 'method' => 'post','name'=>'addAddress_form','files'=>true,'novalidate' => 'novalidate','id' => 'payment-form',"class" => "heidelpayUI form")) !!}
   
  <div class="box">
    <div class="field">
      <div id="card-element-id-number" class="heidelpayInput"></div>
    </div>
    <div class="two fields">
      <div class="field eight wide">
        <div id="card-element-id-expiry" class="heidelpayInput"></div>
      </div>
      <div class="field eight wide">
        <div id="card-element-id-cvc" class="heidelpayInput"></div>
      </div>
    </div>
    <div class="field">
      <button id="payment-button-id" disabled class="heidelpayUI primary button fluid" type="submit">Pay</button>
    </div>
  </div>
   {{ Form::close() }}
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
	<script>
 var heidelpay = new heidelpay('s-pub-2a10ifVINFAjpQJ9qW8jBe5OJPBx6Gxa');
  // Use Card payment type
  var Card = heidelpay.Card();
  // Render the card number input field on #card-element-id-number
  
  Card.create('number', {
    containerId: 'card-element-id-number',
    onlyIframe: false
  });
  // Render the card expiry input field on #card-element-id-expiry
  Card.create('expiry', {
    containerId: 'card-element-id-expiry',
    onlyIframe: false
  });
  // Render the card cvc input field on #card-element-id-cvc
  Card.create('cvc', {
    containerId: 'card-element-id-cvc',
    onlyIframe: false
  });

  var paymentForm = document.getElementById('payment-form');
  var paymentButton = document.getElementById('payment-button-id');
  var paymentFields = {};

  // card events handling
  Card.addEventListener('change', function(e) {
    paymentFields[e.type] = e.success;
    paymentButton.disabled = !(paymentFields.number && paymentFields.expiry && paymentFields.cvc);
  });

  // Handle the form submission.
  paymentForm.addEventListener('submit', function(e) {
    e.preventDefault();
	 $.LoadingOverlay("show");
    // TODO: Prevent further payment form submissions

    // create payment resource using the entered data
    Card.createResource()
      .then(function(data){ 
				//alert(data.id);
				console.log(Card.createResource());
	 // console.log(data);
	  //var card_number = $("#card-number").val(); alert(card_number);
        // TODO: Successful resource creation: submit the id to the server
        //console.log('ResourceID: ' + data.id);
		
		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			url: '{{ URL("vendor/payment-post") }}',
            'type':'get',
            data:{'ResourceID':data.id,'Number':data.number,'expiryDate':data.expiryDate,'Cvv':data.cvv},
            success: function(r){
                //error_array     =   JSON.stringify(r);
                //data            =   JSON.parse(error_array);  
						data            =   r;
                if(data.success == 1) {
					
						showMsg(data.message, "success");
						window.location.href = "{{ URL::to('/vendor') }}";
                }else if(data.success== 2) {
                    //document.getElementById("login_form").reset();
                    //showMessage(data['message'],"error");
					showMsg(data.message, "error");
				}else if(data.success == 3) {
						
					showMessage(data['message'],"error");
					  window.location.href = "{{ URL::to('/') }}/";
						
                }else {
                    $.each(data['message'],function(index,html){ 
                        $("input[name = "+index+"]").next().addClass('error');
                        $("input[name = "+index+"]").next().html(html);
                    });
                }
              $.LoadingOverlay("hide");
            }
        });
		
      })
      .catch(function(error) {
        // TODO: Handle error processing
        console.log(error);
		 $.LoadingOverlay("hide");
		alert(error.message);
		
      });
  });
</script>
@stop 