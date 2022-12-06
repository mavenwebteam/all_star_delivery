@extends('layouts.adminmaster')
@section('title')
All Star Delivery | Store Menu Items
@stop 
@section('content')
    <section class="content-header">
        <h1>Store: {{ $store->name }}</h1>
        <ol class="breadcrumb">
        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Store</li>
        <li class="active">Menu Items</li>
        </ol>
    </section>
    <section class="" style="padding: 15px;">
        <div class="row">
            <div class="col-xs-12 page-user">
                <div class="box">
                    <div class="box-body">
                    {!! Form::open(array('url' => '/admin/store', 'method' => 'post','name'=>'mySearchForm','files'=>true,'id' => 'searchForm')) !!}
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label></label>
                        <select name="item_category" class="form-control">
                            <option value="">-Item Category-</option>
                            @foreach($itemCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label></label>
                        {!! Form::text('item_name',null, ['class' => 'form-control ','placeholder' => 'Item Name' ]) !!}
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label></label>
                        <button style="margin-top: 20px;" id="searchFormBtn" class="btn btn-outline-primary pull-left" type="button">Search</button>
                        <button style="margin-top: 20px;margin-left: 10px;" class="btn btn-outline-success pull-left" type="button" id="formResetBtn">Reset</button>
                    </div>
                    {!! Form::close() !!}
                    </div>
                    <br/>
                </div>
            </div>
        </div>
    </section>
 <div id="msg-data" > </div>
 <section class="content">
    <div class="row">
       <div class="col-xs-12">
          <div class="box">
          <div class="box-header">
              <h3>Menu Items</h3>
          </div>
             <!-- /.box-header -->
            <div class="box-body" id="table-div">
                @include('admin.store.product-table')
            </div>
          </div>
       </div>
    </div>
 </section>


@endsection

@push('script')
    <script>
        
        // ===========Search function===================
        function search() {
        event.preventDefault();
        $.ajax({
            type: "GET",
            enctype: 'multipart/form-data',
            url: '{{ URL::to('admin/store/menu-items') }}'+'/'+ {{ $store->id }},
            data: $('#searchForm').serialize(),
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) { 
                $('#table-div').html(data);
            },
            error: function (e) {
                toastr[data.toster_class](data.msg);
            }
            });
        }

        //========== Search form submit start ============
        $(document).on('click','#searchFormBtn', function(event){
            search();
        });

        //--------reset search form---------
        $(document).on('click','#formResetBtn', function(event){
            resetForm();
        });
        function resetForm() {
            document.getElementById('searchForm').reset();
            search();
        }
    </script>
@endpush