<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel"></div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
     <li class="{{ Request::is('admin') ? 'active' : '' }}">
        <a href="{{ URL::to('/admin') }}">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
     </li>
     <li class="{{ Request::is('admin/report*') ? 'active' : '' }}">
      <a href="{{ route('admin.report') }}">
        <i class="fa fa-line-chart" aria-hidden="true"></i> <span>Report</span>
      </a>
   </li>

     <li class="{{ Request::is('admin/profile*') ? 'active' : '' }}">
        <a href="{{ URL::to('/admin/profile') }}">
        <i class="fa fa-user" aria-hidden="true"></i> <span>Admin Profile</span>
        </a>
     </li>
     
    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
      <a href="{{ URL::to('/admin/users') }}">
      <i class="fa fa-users"></i> <span>Users Manager</span>
      </a>
    </li>
    
    <li class="{{ Request::is('admin/drivers*') ? 'active' : '' }}">
      <a href="{{ route('admin.drivers.index') }}">
      <i class="fa fa-motorcycle"></i> <span>Driver Manager</span>
      </a>
    </li>
    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
      <a href="{{ URL::to('/admin/sub-admin') }}">
        <i class="fa fa-user-circle" aria-hidden="true"></i> <span>SubAdmin Manager</span>
      </a>
    </li>
    <li class="{{ Request::is('admin/banners*') ? 'active' : '' }}">
      <a href="{{ route('admin.banners.index') }}">
      <i class="fa fa-picture-o"></i> <span>Banner Manager</span>
      </a>
    </li>

    <li class="treeview {{ Request::is('admin/business-category*') || Request::is('admin/item-category*') ? 'active' : '' }}">
      <a href="#">
      <i class="fa fa-list-alt"></i> <span>Category Manager</span>
      <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
      </span>
      </a>
      <ul class="treeview-menu">
         <li class="{{ Request::is('admin/business-category*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/business-category') }}"><i class="fa fa-circle-o"></i>Business Category</a></li>
         <li class="{{ Request::is('admin/item-category*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/item-category') }}"><i class="fa fa-circle-o"></i>Item Category</a></li>
      </ul>
    </li>
    <li class="treeview {{ Request::is('admin/store*') || Request::is('admin/vendor*') ? 'active' : '' }}">
      <a href="#">
      <i class="fa fa-shopping-cart"></i> <span>Store Manager</span>
      <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
      </span>
      </a>
      <ul class="treeview-menu">
        <li class="{{ Request::is('admin/vendor*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/vendor') }}"><i class="fa fa-circle-o"></i> Vendor List</a></li>
        <li class="{{ Request::is('admin/store*') ? 'active' : '' }}"><a href="{{ URL::to('/admin/store') }}"><i class="fa fa-circle-o"></i>Store List</a></li>
      </ul>
    </li>
    
    {{-- Order Manager --}}
    <li class="{{ Request::is('admin/order-manager*') ? 'active' : '' }}">
      <a href="{{ route('admin.orders.index') }}">
        <i class="fa fa-cube"></i> <span>Order Manager</span>
      </a>
   </li>
   {{-- Order Manager --}}
   <li class="{{ Request::is('admin/promocode*') ? 'active' : '' }}">
    <a href="{{ route('admin.promocode.index') }}">
      <i class="fa fa-gift" aria-hidden="true"></i> <span>Promocode Manager</span>
    </a>
 </li>
    
    <li class="{{ Request::is('admin/delivery-fee') ? 'active' : '' }}">
      <a href="{{ URL::to('/admin/delivery-fee') }}">
        <i class="fa fa-money" aria-hidden="true"></i> <span>Delivery Fee Manager</span>
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
    <li class="treeview {{ Request::is('admin/setting*') ? 'active' : '' }}">
    <a href="#">
      <i class="fa fa-cog" aria-hidden="true"></i> <span>Setting</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li class="{{ Request::is('admin/setting*') ? 'active' : '' }}">
        <a href="{{ route('admin.setting.index') }}">
          <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Admin Setting</span>
        </a>
      </li>
    </ul>
    </li>
  </ul>
</section>