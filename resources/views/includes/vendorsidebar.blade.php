<style>
  .sidebar-menu>li>a span.count {
    position: absolute;
    right: 11px;
    background: #dd4b39;
    color: #fff;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 10px;
    border-radius: 50%;
    font-size: 12px;
    line-height: 10px;
}
</style>

 <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel"></div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="{{ Request::is('vendor') ? 'active' : '' }}">
          <a href="{{ URL::to('/vendor') }}">
            <i class="fa fa-dashboard"></i> <span>{{  __('vendor.menu_dashboard') }}</span>
          </a>
        </li>
        
        {{-- vendor profile --}}
        <li class="{{ Request::is('vendor/profile') ? 'active' : '' }}">
          <a href="{{ URL::to('/vendor/profile') }}">
            <i class="fa fa-user" aria-hidden="true"></i> <span>{{  __('vendor.menu_profile') }}</span>
          </a>
        </li>

        {{-- store profile --}}
        <li class="{{ Request::is('vendor/store-profile*') ? 'active' : '' }}">
          <a href="{{ URL::to('/vendor/store-profile') }}">
            <i class="fa fa-shopping-cart"></i> <span>{{  __('vendor.menu_store_profile') }}</span>
          </a>
        </li>
        {{-- When vensor store is not available hide this section --}}
        @php $Result= Helper::vendorHasActiveStore(Auth::id()); @endphp
        @if($Result)
        {{-- Menu Manager --}}
        <li class="{{ Request::is('vendor/menu-manager*') ? 'active' : '' }}">
          <a href="{{ URL::to('/vendor/menu-manager') }}">
            <i class="fa fa-list-alt"></i> <span>{{  __('vendor.menu_menu_manager') }}</span>
          </a>
        </li>
        {{-- Order Manager --}}
        <li class="treeview {{ Request::is('vendor/orders*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-cube"></i> <span>{{  __('vendor.menu_order_manager') }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('vendor/orders') ? 'active' : '' }}"><a href="{{ route('vendor.orders.index') }}"><i class="fa fa-paper-plane-o"></i>{{  __('vendor.menu_recent_order') }}</a></li>
            <li class="treeview {{ Request::is('vendor/orders/order-history*') ? 'active' : '' }}">
              <a href="#"><i class="fa fa-clock-o"></i> Order History
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Request::is('vendor/orders/order-history/current-orders') ? 'active' : '' }}"><a href="{{ route('vendor.orders.current') }}"><i class="fa fa-circle-o"></i> {{  __('vendor.menu_current_order') }}</a></li>
                <li class="{{ Request::is('vendor/orders/order-history/past-orders') ? 'active' : '' }}"><a href="{{ route('vendor.orders.past') }}"><i class="fa fa-circle-o"></i>{{  __('vendor.menu_past_order') }}</a></li>
              </ul>
            </li>
          </ul>
        </li>
        @endif

        <li class="{{ Request::is('vendor/contact*') ? 'active' : '' }}">
          <a href="{{ URL::to('/vendor/contact') }}">
            <i class="fa fa-phone" aria-hidden="true"></i> <span>{{  __('vendor.menu_contact') }}</span>
          </a>
        </li>
        <li class="{{ Request::is('vendor/setting') ? 'active' : '' }}">
          <a href="{{ route('vendor.setting.edit') }}">
            <i class="fa fa-cog" aria-hidden="true"></i> <span>{{  __('vendor.setting') }}</span>
          </a>
        </li>
      </ul>
    </section>