<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="{{ asset('admin_assets\toastr\toastr.min.css') }}">
 <script type="text/javascript" src="{{ asset('admin_assets\toastr\toastr.min.js') }}"></script>
<style>
.btn{margin-top:5px;}
.pr-0 {
    padding-right: 0px;
}
</style>
@include('includes.adminhead')
@stack('styles')
</head>
<body class="hold-transition skin-black-light sidebar-mini">
<div class="wrapper">
<aside class="right-side">
  @if(Session::has('flash_notice'))
      <script type="text/javascript"> 
          $(document).ready(function(e){
              showMessage("{{{ Session::get('flash_notice') }}}",'success');
          });
      </script>
  @endif
						
@if(Session::has('flash_error'))
  <script type="text/javascript"> 
      $(document).ready(function(e){
          showMessage("{{{ Session::get('flash_error') }}}",'error');
      });
  </script>
@endif
</aside>
  <header class="main-header">
  @include('includes.adminheader')
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    @include('includes.adminsidebar')


    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 946.3px!important;">

    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  @include('includes.adminfooter')

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
function checklogin(){
			 var returnValue;
			$.ajax({
				type: "get",
				async: false,
				url: '{{ URL("admin/checkuserlogin") }}',
				dataType: 'json',
				contentType: 'application/json; charset=utf-8',
				data: JSON.stringify({ name: name }),
				success: function (data) { //alert(data);
					//returnValue = data.d;
					if(data == 2){ 
					returnValue = false;
						//location.reload();
						//$.LoadingOverlay("hide");
						//return false;
					}else{
						//return true;
						returnValue = true;
					}
				}
			});
			return returnValue;
		};

$.LoadingOverlay("show");
// Hide it after 3 seconds
setTimeout(function(){
    $.LoadingOverlay("hide");
}, 1000);


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
        $('[data-toggle="tooltip"]').tooltip();
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
