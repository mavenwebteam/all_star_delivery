<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/toastr/toastr.min.css')}}">
    <style>
      .btn{margin-top:5px;}
      .pr-0 {
          padding-right: 0px;
      }
    </style>
    @include('includes.sub_admin_head')
    @stack('styles')
  </head>
  <body class="hold-transition skin-black-light sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        @include('includes.sub_admin_header')
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('includes.sub_admin_sidebar')
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="min-height: 946.3px!important;">
        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      @include('includes.sub_admin_footer')
      <!-- Add the sidebar's background. This div must be placed
          immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    <script>
      function checklogin() {
          var returnValue;
          $.ajax({
            type: "get",
            async: false,
            url: '{{ URL("sub-admin/checkuserlogin") }}',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            success: function (data) { 
              if(data == 2){ 
              returnValue = false;
              }else{
                returnValue = true;
              }
            }
          });
          return returnValue;
      };
    </script>
    {{-- notification using toastr --}}
    <script src="{{ asset('admin_assets/toastr/toastr.min.js')}}"></script>
    {{-- pop-up notification --}}
    <script type="text/javascript">
      toastr.options = {
          "closeButton": true,
          "progressBar": true,
      };
      @if(session()->has('success'))
        toastr.success("{{session()->get('success')}}")
      @endif

      @if(session()->has('error'))
        toastr.error("{{session()->get('error')}}")
      @endif

      @if(session()->has('warning'))
        toastr.warning("{{session()->get('warning')}}")
      @endif

      @if(session()->has('info'))
        toastr.info("{{session()->get('info')}}")
      @endif
    </script>

    <script>
      // =====loading overlay show on ajax start====
      $( document ).ajaxStart(function() {
        $.LoadingOverlay("show");
      });

      $( document ).ajaxStop(function() {
        $.LoadingOverlay("hide");
      });

      $.LoadingOverlay("show");

      // Hide it after 1 seconds
      setTimeout(function(){
          $.LoadingOverlay("hide");
      }, 1000);

      // ======common ajax setup=========
      $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')  }
      });
    </script>
      @stack('script')
  </body>
</html>
