 <!-- Logo -->
 <style>
 .navbar-custom-menu>.navbar-nav>li img {
    width: 35px;
    height: 35px;
    position: absolute;
    right: 100px;
    border-radius: 50%;
    top: 8px;
}</style>
@php 
$helper = new App\Helpers; 
$isStoreOnline = $helper->isStoreOnline(Auth::id());

@endphp
    <a href="{{ URL::to('/vendor') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">{{ __('vendor.panel_heading') }}</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">{{ __('vendor.panel_heading') }} 
        @if($isStoreOnline)<i class="fa fa-circle on_off" style="color:#59c15b" data-toggle="tooltip" data-placement="bottom" title="Store Online"></i>
        @else <i data-toggle="tooltip" data-placement="bottom" title="Store offline" class="fa fa-circle on_off" style="color:rgb(173, 182, 173)"></i>
        @endif
      </span>
    </a>
    {{-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> --}}
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      @if(App::isLocale('en'))
      <a style="margin-left: 0.8%;" href="{{route('language','my')}}" class="btn btn-primary"><i class="fa fa-language" aria-hidden="true"></i> Burmese</a>
      @else
      <a style="margin-left: 0.8%;" href="{{route('language','en')}}" class="btn btn-primary"><i class="fa fa-language" aria-hidden="true"></i> English</a>
      @endif
      <div class="material-switch pull-right pt">
        <input  @if($isStoreOnline) checked @endif id="storeOnOff" name="is_open" value="1" type="checkbox" onclick="storeOnlineOffline()" />
        <label for="storeOnOff" class="label-success" data-toggle="tooltip" data-placement="right" title="Store Online/Offline"></label>
      </div>
      


      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            @if(Auth::user()->profile_pic!="")
              <img src="{{asset('/media/users').'/'.Auth::user()->profile_pic}}" width="70px" height="70px">
            @else
                <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
            @endif
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <span class="hidden-xs">{{Auth::user()->first_name}}</span>
            </a>
            <ul class="dropdown-menu">   
              <li><a href="{{ URL::to('/vendor/logout') }}" class="dropdown-item">{{ __('vendor.sign_out_btn') }}</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    @push('script')
    <script>
      /**
       * Change store status online/offline
      */
      function storeOnlineOffline() 
      {	
        let is_open = $("input[type=checkbox][name=is_open]:checked" ).val();
        let id = {{ Auth::id() }} ;
        var checklogin1 = checklogin();
        if(checklogin1  == true){
          $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      }
                  });
          $.ajax({
                dataType: 'json',
                data: { id:id, is_open}, 
                type: "POST",
                url: '{{ URL::to('/vendor/store/on-off') }}',
            }).done(function( data ) 
            {
              if(data.class == 'success')
                {  
                if(is_open){
                  $(".on_off").css("color","#59c15b")
                }else{
                  $(".on_off").css("color","rgb(173, 182, 173)")
                }
                $(".alert-success").remove();
                showMsg(data.message, data.class);}
            });
        }else{
          location.reload();
          $.LoadingOverlay("hide");
        }  
      };
    </script>
    @endpush