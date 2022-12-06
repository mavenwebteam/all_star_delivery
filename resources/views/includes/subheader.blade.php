<section class="catgory-outer">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="catgory-list">
            	<li><a href="{{ URL::to('/') }}" class="{{ Request::is('shop*') ? 'active' : '' }}" ><span><img src="{{asset('/assets/img/home.png') }}" alt="" class="no-hover"> <img src="{{asset('/assets/img/home-hover.png') }}" alt="" class="on-hover" style="display: none;" ></span>Home</a></li>
            	<li><a href="{{ URL::to('/categories') }}" class="{{ Request::is('categories*') ? 'active' : '' }}"><span><img src="{{asset('/assets/img/categories.png') }}" alt="" class="no-hover"> <img src="{{asset('/assets/img/categories-hover.png') }} " alt="" class="on-hover" style="display: none;"></span>Categories</a></li>
				<?php if(!empty(Auth::user())){?>
            	<li><a href="{{ URL::to('/users/orders') }}" class="{{ Request::is('users/orders*') ? 'active' : '' }}"><span><img src="{{asset('/assets/img/orders.png') }} " alt="" class="no-hover"> <img src="{{asset('/assets/img/orders-hover.png') }} " alt="" class="on-hover" style="display: none;"></span>Orders</a></li>
				<?php } ?>
            	
            </div>
			
          </div>
        </div>
      </div>
    </section>