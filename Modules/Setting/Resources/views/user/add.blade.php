<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("setting::user.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("setting::user.navbar")
  @include( "sidebar",['user' => $user, 'config_sidebar' => $config_sidebar] )

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Welcome
        <small><strong>{{ $user->adjusters->name}}</strong></small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add User</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" method="post" action="{{ url('/')}}/setting/user/create">
                {{ csrf_field() }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
          
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  @include ("copyright")
 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@include("setting::user.footer");
<script type="text/javascript">
   $('#example4').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
</script>
</body>
</html>
