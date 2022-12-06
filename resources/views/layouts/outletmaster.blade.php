<!DOCTYPE html>
<html>
<head>
@include('includes.outlethead')
</head>
<body class="hold-transition skin-black-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
  @include('includes.outletheader')
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    @include('includes.outletsidebar')


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
 
  @include('includes.outletfooter')

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
				url: '{{ URL("outlet/checkuserlogin") }}',
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
		}
</script>

</body>
</html>
