<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel"></div>
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
      @if(in_array('subAdmin.dashboard', $permissionData))
      <li class="{{ Request::is('sub-admin') ? 'active' : '' }}">
          <a href="{{ URL::to('/sub-admin') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
      </li>
      @endif
      @if(in_array('subAdmin.users.index', $permissionData))
      <li class="{{ Request::is('sub-admin/users*') ? 'active' : '' }}">
        <a href="{{ URL::to('/sub-admin/users') }}">
        <i class="fa fa-users"></i> <span>Users Manager</span>
        </a>
      </li>
      @endif
      @if(in_array('subAdmin.drivers.index', $permissionData))
      <li class="{{ Request::is('sub-admin/drivers*') ? 'active' : '' }}">
        <a href="{{ route('subAdmin.drivers.index') }}">
        <i class="fa fa-motorcycle"></i> <span>Driver Manager</span>
        </a>
      </li>
      @endif
      @if(in_array('subAdmin.banners.index', $permissionData))
      <li class="{{ Request::is('sub-admin/banners*') ? 'active' : '' }}">
        <a href="{{ route('subAdmin.banners.index') }}">
        <i class="fa fa-picture-o"></i> <span>Banner Manager</span>
        </a>
      </li>
      @endif
    @if(in_array('subAdmin.business-category.index', $permissionData) || in_array('subAdmin.item-category.index', $permissionData))
    <li class="treeview {{ Request::is('sub-admin/business-category*') || Request::is('sub-admin/item-category*') ? 'active' : '' }}">
      <a href="#">
      <i class="fa fa-list-alt"></i> <span>Category Manager</span>
      <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
      </span>
      </a>
      <ul class="treeview-menu">
        @if(in_array('subAdmin.business-category.index', $permissionData))
         <li class="{{ Request::is('sub-admin/business-category*') ? 'active' : '' }}"><a href="{{ URL::to('/sub-admin/business-category') }}"><i class="fa fa-circle-o"></i>Business Category</a></li>
        @endif
        @if(in_array('subAdmin.item-category.index', $permissionData))
         <li class="{{ Request::is('sub-admin/item-category*') ? 'active' : '' }}"><a href="{{ URL::to('/sub-admin/item-category') }}"><i class="fa fa-circle-o"></i>Item Category</a></li>
        @endif
      </ul>
    </li>
    @endif
    @if(in_array('subAdmin.vendor.index', $permissionData) || in_array('subAdmin.store.index', $permissionData))
    <li class="treeview {{ Request::is('sub-admin/store*') || Request::is('sub-admin/vendor*') ? 'active' : '' }}">
      <a href="#">
      <i class="fa fa-shopping-cart"></i> <span>Store Manager</span>
      <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
      </span>
      </a>
      <ul class="treeview-menu">
        @if(in_array('subAdmin.vendor.index', $permissionData))
        <li class="{{ Request::is('sub-admin/vendor*') ? 'active' : '' }}"><a href="{{ URL::to('/sub-admin/vendor') }}"><i class="fa fa-circle-o"></i> Vendor List</a></li>
        @endif
        @if(in_array('subAdmin.store.index', $permissionData))
        <li class="{{ Request::is('sub-admin/store*') ? 'active' : '' }}"><a href="{{ URL::to('/sub-admin/store') }}"><i class="fa fa-circle-o"></i>Store List</a></li>
        @endif
      </ul>
    </li>
    @endif
    
    {{-- Order Manager --}}
    @if(in_array('subAdmin.orders.index', $permissionData))
    <li class="{{ Request::is('sub-admin/order-manager*') ? 'active' : '' }}">
      <a href="{{ route('subAdmin.orders.index') }}">
        <i class="fa fa-cube"></i> <span>Order Manager</span>
      </a>
   </li>
   @endif
   @if(in_array('subAdmin.promocode.index', $permissionData))
    {{-- Order Manager --}}
    <li class="{{ Request::is('sub-admin/promocode*') ? 'active' : '' }}">
      <a href="{{ route('subAdmin.promocode.index') }}">
        <i class="fa fa-gift" aria-hidden="true"></i> <span>Promocode Manager</span>
      </a>
    </li>
    @endif
    @if(in_array('subAdmin.delivery-fee.index', $permissionData))
    <li class="{{ Request::is('sub-admin/delivery-fee') ? 'active' : '' }}">
      <a href="{{ URL::to('/sub-admin/delivery-fee') }}">
        <i class="fa fa-money" aria-hidden="true"></i> <span>Delivery Fee Manager</span>
      </a>
    </li>
    @endif
    @if(in_array('subAdmin.content.index', $permissionData))
    <li class="{{ Request::is('sub-admin/content*') ? 'active' : '' }}">
      <a href="{{ URL::to('/sub-admin/content') }}">
      <i class="fa fa-file-text-o"></i> <span>Content Manager</span>
      </a>
    </li>
   @endif
   @if(in_array('subAdmin.email-templates.index', $permissionData))
   <li class="{{ Request::is('sub-admin/email-templates*') ? 'active' : '' }}">
      <a href="{{ URL::to('/sub-admin/email-templates') }}">
      <i class="fa fa-envelope"></i> <span>Email Templates Manager</span>
      </a>
   </li>
   @endif

   @if(in_array('subAdmin.changePassword', $permissionData))
    <li class="treeview {{ Request::is('sub-admin/setting*') ? 'active' : '' }}">
    <a href="#">
      <i class="fa fa-cog" aria-hidden="true"></i> <span>Setting</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li class="{{ Request::is('sub-admin/change-password') ? 'active' : '' }}">
        <a href="{{ route('subAdmin.changePassword') }}">
          <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Change Password</span>
        </a>
      </li>
    </ul>
    </li>
    @endif
  </ul>
</section>