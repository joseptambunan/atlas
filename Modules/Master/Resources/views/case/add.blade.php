<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::adjuster.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("master::adjuster.navbar")
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
              <h3 class="box-title">Master Adjuster</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/master/adjusters/create">
                {{ csrf_field() }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="name" name="name" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Positions</label>
                    <select class="form-control" name="positions">
                      @foreach ( $master_positions as $key => $value )
                        <option value="{{ $value->id }}">{{ $value->position_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                      <label>File input</label>
                      <input type="file" id="thumbnail" name="thumbnail">
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
@include("master::document.footer");
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
