<span class="custon-sidebar">
<?php use App\Http\Controllers\Controller;  ?>
			          <nav class="side_bar">
			              <div class="menu_nav">
							  <div class="my-account">
								  <h2>My Account</h2>
							  </div>
			                  <ul>
			                      <li><a href="{{ URL::to('/myaccount') }}"  class="{{ Request::is('myaccount*') ? 'active' : '' }}">Profile</a></li>
								   <li><a href="{{ URL::to('/refund_amount_history') }}"  class="{{ Request::is('refund_amount_history*') ? 'active' : '' }}">Wallet Amount : $<?php echo Auth::user()->wallet_amount; ?></a></li>
								   <li><a href="{{ URL::to('/users/address') }}"  class="{{ Request::is('users/address*') ? 'active' : '' }}">Manage Address</a></li>
			                      <li><a href="{{ URL::to('/categories') }}" class="{{ Request::is('categories*') ? 'active' : '' }}">Categories</a></li>
			                      <li><a href="{{ URL::to('/users/orders') }}" class="{{ Request::is('users/orders*') ? 'active' : '' }}">Orders</a></li>
			                     <!--<li><a href="{{ URL::to('users/favourites') }}" class="{{ Request::is('favourites*') ? 'active' : '' }}">Favourites</a></li>-->
			                      <li><a href="{{ URL::to('users/notification') }}"  class="{{ Request::is('users/notification*') ? 'active' : '' }}">Notifications</a></li>
			                      <li><a href="{{ URL::to('/logout') }}">Logout</a></li>
			                      
			                  </ul>
			              </div>
			          </nav>
			          <div class="overlay"></div>
			        </span>