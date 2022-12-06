<script type="text/javascript">

    $(document).ready(function () 
    {   
        $.LoadingOverlay("hide");
        $( '#orderForm').on( 'submit', function(e) 
        {   
            $.LoadingOverlay("show");		
            var checklogin1 = checklogin();		
            if(checklogin1  == true)
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
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: '{{ route("admin.orders.update",$order->id) }}',
                }).done(function(data){ 
                    console.log(data);
                    error_remove ();
                    $.LoadingOverlay("hide");  
                    if(data.success==false)
                    {
                        $.each(data.errors, function(key, value) {
                            $('#' + key).parent().addClass('form-group has-error');
                            $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('#' + key));
                        });
                        if(data.msg){
                            showMsg(data.msg, "error");
                        }
                    }else{
                        $.LoadingOverlay("hide");  
                        showMsg(data.msg, "success");             
                        $("#modal-lg").modal('hide');
                        search();
                        return false;
                    }
                    $.LoadingOverlay("hide");     
                });
                $.LoadingOverlay("hide");  
            }
            else
            {
                location.reload();
                $.LoadingOverlay("hide");		
            }  
        });
    });
</script>

{!! Form::open(array('url' => route('admin.orders.update',$order->id), 'method' => 'post', 'name'=>'orderForm','files' =>'true','novalidate' => 'novalidate','id' => 'orderForm')) !!}
    @method('PUT')    
    <div class="col-sm-12 p-r-30">
        <div class="panel panel-transparent">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <label>Order Id<span class="red-star">*</span></label>
                        <input type="text" value={{ $order->order_id }} class="form-control" readonly>
                    </div>
                    @if($assignedDriver)
                    <div class="col-xs-6">
                        <label>Assigned Driver<span class="red-star">*</span></label>
                        <input type="text" value="{{ $assignedDriver->fullName.'('.$assignedDriver->id.')' }}" class="form-control" readonly="true">
                    </div>
                    @else
                    <div class="col-xs-6">
                        <label for="name_br">Drivers<span class="red-star">*</span></label>
                        <select name="driver_id" id="driver_id" class="form-control">
                            <option value="">-Choose Driver-</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" @if($driver->id == $order->driver_id) selected @endif>{{ $driver->full_name }} ( {{ $driver->id }} )</option>
                            @endforeach
                        </select> 
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <label for="status">Order Status<span class="red-star">*</span></label>
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="">-Order Status-</option>
                            <option value="CANCELLED" @if($order->status == 'CANCELLED') selected @endif>Cancelled</option>
                            <option value="ACCEPTED" @if($order->status == 'ACCEPTED') selected @endif>Accepted by store</option>
                            <option value="DELIVERED" @if($order->status == 'DELIVERED') selected @endif>Delivered</option>
                        </select> 
                    </div>
                    <div class="col-xs-6">
                        <div id="reason">
                            <label for="reason_of_cancel">Reason of cancel</span class="red-star">*</span></label>
                            <textarea name="reason_of_cancel" id="reason_of_cancel" class="form-control">{!! $order->reason_of_cancel !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-btn text-center">
        <div class="col-sm-12 p-r-30">
        <div class="col-md-12">
            {!! Form::submit(trans('vendor.save_btn'),['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
            {!! Form::submit('Cancel', ['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
        </div>
        </div>
    </div>
    {!! Form::close() !!}

    {{-- <script src="{{asset('/admin_assets/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script>
    $(function() {
    
        // Replace the <textarea id="editor1"> with a CKEditor
    
        // instance, using default configuration.
    
        CKEDITOR.replace('description_en')
        CKEDITOR.replace('description_br')
    
        //bootstrap WYSIHTML5 - text editor
    
        $('.textarea').wysihtml5()
    
    });
    </script> --}}

    <script>
        // -------render textarea if order status is cancelled------
        function renderTextarea() {
            let status = $("#order_status").val();
           if(status == "CANCELLED"){
               $("#reason").show("slow");
           }else{
            $("#reason").hide("slow");
           }
        }
        $("#order_status").on('change', function(){
           renderTextarea();
        });

        $( document ).ready(function() {
            renderTextarea();
        });
    </script>
   