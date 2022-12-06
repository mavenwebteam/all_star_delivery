
<ul>
  @if(Auth::user()->user_type=="agent")
  <li class="{{ request()->is('myaccount') ? 'active' : '' }}"><a href="{{URL::to('myaccount')}}">Dashbord <i class="fa fa fa-dashboard"></i></a></li>
  <li class="{{ request()->is('myaccount/companies') ? 'active' : '' }}"><a href="{{URL::to('myaccount/companies')}}">Companies <i class="fa fa-file-archive-o"></i></a></li>
  <?php /*?><li><a href="#">Payment History <i class="fa fa-credit-card"></i></a></li>
  <li><a href="#">company Activities <i class="fa fa-history"></i></a></li><?php */?>
  <li class="{{ request()->is('myaccount/security') ? 'active' : '' }}"><a href="{{URL::to('myaccount/security')}}">Security <i class="fa fa-lock"></i></a></li>
  <li class="{{ request()->is('myaccount/change-password') ? 'active' : '' }}"><a href="{{URL::to('myaccount/change-password')}}">Change Password <i class="fa fa-lock"></i></a></li>
  <li><a href="{{URL::to('logout')}}">Logout <i class="fa fa-sign-out"></i></a></li>
  @else
  <li class="{{ request()->is('myaccount') ? 'active' : '' }}"><a href="{{URL::to('myaccount')}}">Dashbord <i class="fa fa fa-dashboard"></i></a></li>
  <li class="{{ request()->is('myaccount/company-info*') ? 'active' : '' }}"><a href="{{URL::to('myaccount/company-info')}}">Company Info <i class="fa fa-info-circle"></i></a></li>
  <?php /*?><li><a href="#">Company Activities <i class="fa fa-history"></i></a></li>
  <li><a href="#">Payment History <i class="fa fa-credit-card"></i></a></li>
  <li class="{{ request()->is('mydocument*') ? 'active' : '' }}"><a href="{{URL::to('/mydocument')}}">Document Download <i class="fa fa-download"></i></a></li><?php */?>
  <li class="{{ request()->is('myaccount/grievance*') ? 'active' : '' }}"><a href="{{URL::to('myaccount/grievance')}}">Grievance <i class="fa fa-recycle"></i></a></li>
   <li class="{{ request()->is('myaccount/cdsl-credential') ? 'active' : '' }}"><a href="{{URL::to('myaccount/cdsl-credential')}}">CDSL Credential <i class="fa fa-comments"></i></a></li>

   <li class="{{ request()->is('myaccount/documents*') ? 'active' : '' }}"><a href="{{URL::to('myaccount/documents')}}">Documents <i class="fa fa-file"></i></a></li>
   <li class="{{ request()->is('myaccount/billing') ? 'active' : '' }}"><a href="{{URL::to('myaccount/billing')}}">Billing <i class="fa fa-file-text-o"></i></a></li>

  <li class="{{ request()->is('myaccount/security') ? 'active' : '' }}"><a href="{{URL::to('myaccount/security')}}">Security <i class="fa fa-lock"></i></a></li>
  <li class="{{ request()->is('myaccount/change-password') ? 'active' : '' }}"><a href="{{URL::to('myaccount/change-password')}}">Change Password <i class="fa fa-edit"></i></a></li>
  <li><a href="{{URL::to('logout')}}">Logout <i class="fa fa-sign-out"></i></a></li>
  @endif
</ul>
