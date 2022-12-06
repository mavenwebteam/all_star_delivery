<section class="banner-box">
      <div class="container">
	   {{ Form::open(['role' => 'form','url' => "search-product",'id'=>'search_product','method'=>'get']) }}
	    {!! Form::hidden('store',((isset($store)) ? $store : ''),['id'=>'id']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="service-outer">
			
              <div>
				  {!! Form::text('searchInput',((isset($searchInput)) ? $searchInput : ''), ['class' => 'form-control','placeholder' => 'Bakery, Buscuits, Snacks Baby products','id'=>'searchInput','onkeypress' => 'error_remove()' ]) !!}
                 <button class="search-box"><i class="fas fa-search"></i></button>
              </div>
			
            </div>
          </div>
        </div>
		  {{ Form::close() }} 
      </div>
    </section>