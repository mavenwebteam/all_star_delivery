 <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel"></div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="{{ Request::is('outlet') ? 'active' : '' }}">
          <a href="{{ URL::to('/outlet') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
       
		<!-- <li class="{{ Request::is('outlet/profile*') ? 'active' : '' }}">
          <a href="{{ URL::to('/outlet/profile') }}">
            <i class="fa fa-users"></i> <span>Vendor Manager</span>
          </a>
        </li>
		 <li class="treeview {{ Request::is('admin/subadmin*') || Request::is('admin/subadmin*')  ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>Sub Admin Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/subadmin*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/subadmin') }}"><i class="fa fa-circle-o"></i>Sub Admin List</a></li>
            <li class="{{ Request::is('admin/logs*') ? 'active' : '' }}"><a href="javascript:;"><i class="fa fa-circle-o"></i> Logs</a></li>
            
          </ul>
        </li>
		 <li class="{{ Request::is('outlet/picker*') ? 'active' : '' }}">
          <a href="{{ URL::to('/outlet/picker') }}">
            <i class="fa fa-users"></i> <span>Picker Manager</span>
          </a>
        </li>
		
       
       <!-- <li class="{{ Request::is('admin/weightunit*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/weightunit') }}">
            <i class="fa fa-list-alt"></i> <span>Weight Unit Manager</span>
          </a>
        </li>-->
         
        <!--<li class="treeview {{ Request::is('admin/country*') || Request::is('admin/city*') || Request::is('admin/area*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-map-marker"></i> <span>Location Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/country*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/country') }}"><i class="fa fa-circle-o"></i>Country</a></li>
            <li class="{{ Request::is('admin/city*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/city') }}"><i class="fa fa-circle-o"></i> City</a></li>
            <li class="{{ Request::is('admin/area*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/area') }}"><i class="fa fa-circle-o"></i> Area</a></li>
          </ul>
        </li> -->
		
		<!-- <li class="treeview {{ Request::is('admin/store*') || Request::is('admin/package*')  ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>Store Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
		  
		  
		  <li class="{{ Request::is('admin/vendor*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/vendor') }}"><i class="fa fa-circle-o"></i> Vendor List</a></li>
              
            <li class="{{ Request::is('admin/package*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/package') }}"><i class="fa fa-circle-o"></i> PackageList</a></li>
             <li class="{{ Request::is('admin/store*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/store') }}"><i class="fa fa-circle-o"></i>Store List</a></li>
          </ul>
        </li> -->
		
       
		
		 <li class="treeview {{ Request::is('outlet/category*') || Request::is('outlet/subcategory*') || Request::is('outlet/brand*') || Request::is('outlet/product')   || Request::is('outlet/product-inventory*') || Request::is('admin/weight-unit*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-list-alt"></i> <span>Product Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('outlet/product*') ? 'active' : '' }}"><a href="{{ URL::to('/outlet/product') }}"><i class="fa fa-circle-o"></i>Products</a></li>
            <li class="{{ Request::is('outlet/product-inventory*') ? 'active' : '' }}"><a href="{{ URL::to('/outlet/product-inventory') }}"><i class="fa fa-circle-o"></i>Products Inventory</a></li>
			<!--<li class="{{ Request::is('admin/weight-unit*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/weight-unit') }}"><i class="fa fa-circle-o"></i>Weight Unit Manager</a></li>-->

          </ul>
        </li>
		 <!--<li class="{{ Request::is('admin/zone*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/zone') }}">
            <i class="fa fa-shopping-cart"></i> <span>Zone Manager</span>
          </a>
        </li>-->
      
		
		<!-- <li class="treeview {{ Request::is('admin/orders*') || Request::is('admin/assign_delivery_boy*') || Request::is('admin/return_item*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-list-alt"></i> <span>Orders Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li class="{{ Request::is('outlet/orders*') ? 'active' : '' }}"><a href="{{ URL::to('/outlet/orders') }}"><i class="fa fa-circle-o"></i>Order Listing</a></li>
           <!-- <li class="{{ Request::is('admin/assign_delivery_boy*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/assign_delivery_boy') }}"><i class="fa fa-circle-o"></i>Assign Delivery Boys</a></li>
            <li class="{{ Request::is('admin/return_item*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/return_item') }}"><i class="fa fa-circle-o"></i>Return Items</a></li>
            

          </ul>
        </li>
		-->
		
		<!--<li class="{{ Request::is('outlet/vendor-rating*') ? 'active' : '' }}">
          <a href="{{ URL::to('/outlet/vendor-rating') }}">
            <i class="fa fa-shopping-cart"></i> <span>Rating & Review Manager</span>
          </a>
        </li>-->
		
          <li class="{{ Request::is('outlet/orders*') ? 'active' : '' }}">
          <a href="{{ URL::to('/outlet/orders') }}">
            <i class="fa fa-shopping-cart"></i> <span>Orders Manager</span>
          </a>
        </li>
		 
		 <li class="treeview {{ Request::is('outlet/earning-manager*') || Request::is('outlet/invoice*')  ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-list-alt"></i> <span>Earning Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li class="{{ Request::is('outlet/earning-manager*') ? 'active' : '' }}"><a href="{{ URL::to('/outlet/earning-manager') }}"><i class="fa fa-circle-o"></i>Earning Listing</a></li>
            <!--<li class="{{ Request::is('outlet/invoice*') ? 'active' : '' }}"><a href="{{ URL::to('/outlet/invoice') }}"><i class="fa fa-circle-o"></i>Store Invoice List</a></li>-->
          
            

          </ul>
        </li>
		
		<!--<li class="treeview {{ Request::is('admin/delivery-boy*') || Request::is('admin/cash-order-limit*') || Request::is('admin/delivery-price-manager*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>Delivery Boy manager </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/delivery-boy*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/delivery-boy') }}"><i class="fa fa-circle-o"></i>Delivery Boy List</a></li>
            <li class="{{ Request::is('admin/cash-order-limit*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/cash-order-limit') }}"><i class="fa fa-circle-o"></i> Cash Limit Manager</a></li>
			<li class="{{ Request::is('admin/delivery-price-manager*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/delivery-price-manager') }}"><i class="fa fa-circle-o"></i> Delivery Price Manager</a></li>
            
          </ul>
        </li> 
		 <!--<li class="{{ Request::is('admin/delivery-price-manager*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/delivery-price-manager') }}">
            <i class="fa fa-envelope"></i> <span>Delivery Price Manager</span>
            
          </a>
        </li> -->
        <!--<li class="{{ Request::is('admin/cash-order-limit*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/cash-order-limit') }}">
            <i class="fa fa-money"></i> <span>Cash Limit Manager</span>
          </a>
        </li>-->
       <!-- <li class="{{ Request::is('admin/payment*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/payment') }}">
            <i class="fa fa-money"></i> <span>Payment Manager</span>
          </a>
        </li>-->
        <!--<li class="{{ Request::is('admin/package*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/package') }}">
            <i class="fa fa-money"></i> <span>Package Manager</span>
          </a>
        </li>-->
       <!-- <li class="treeview {{ Request::is('admin/product-rating*') || Request::is('admin/vendor-rating*')  ? 'active' : '' }}">
          <a href="#">
            <i class="fa  fa-star"></i> <span>Review Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!-- <li class="{{ Request::is('admin/product-rating*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/product-rating') }}"><i class="fa fa-circle-o"></i>Product Rating</a></li> -->
            <!--<li class="{{ Request::is('admin/vendor-rating*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/vendor-rating') }}"><i class="fa fa-circle-o"></i> Vendor Rating</a></li>
          </ul>
        </li> 
        <!-- <li class="treeview {{ Request::is('admin/dispute-request*') || Request::is('admin/payment-request*')  ? 'active' : '' }}"">
          <a href="#">
            <i class="fa fa-bullhorn"></i> <span>Request Manager</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/dispute-request*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/dispute-request') }}"><i class="fa fa-circle-o"></i> Dispute Request</a></li>
            <li clas="{{ Request::is('admin/payment-request*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/payment-request') }}"><i class="fa fa-circle-o"></i>Payment Request</a></li>
          </ul>
        </li> -->
        <!-- <li class="{{ Request::is('admin/earning*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/earning') }}">
            <i class="fa fa-money"></i> <span>Earning Manager</span>
          </a>
        </li> -->
       <!-- <li class="{{ Request::is('admin/notification*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/notification') }}">
            <i class="fa fa-bell"></i> <span>Notification Manager</span>
          </a>
        </li>
        <li class="{{ Request::is('admin/issue*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/issue') }}">
            <i class="fa fa-bug"></i> <span>Issue Manager</span>
          </a>
        </li>
        <li class="{{ Request::is('admin/referral*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/referral') }}">
            <i class="fa fa-bug"></i> <span>Referral Manager</span>
          </a>
        </li>
        <li class="{{ Request::is('admin/setting*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/setting') }}">
            <i class="fa fa-cogs"></i> <span>Setting Manager</span>
          </a>
        </li>
        <li class="{{ Request::is('admin/content*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/content') }}">
            <i class="fa fa-file-text-o"></i> <span>Content Manager</span>
            
          </a>
        </li>
        <li class="{{ Request::is('admin/email-templates*') ? 'active' : '' }}">
          <a href="{{ URL::to('/admin/email-templates') }}">
            <i class="fa fa-envelope"></i> <span>Email Templates Manager</span>
            
          </a>
        </li> 
       
        <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li> -->
       
      </ul>
    </section>