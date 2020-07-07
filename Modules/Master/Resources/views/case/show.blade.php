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
              <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/master/adjusters/update">
                {{ csrf_field() }}
                <input type="hidden" name="adjuster_id" value="{{ $adjuster->id }}">
                <div class="box-body">
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="name" name="name" autocomplete="off" value="{{ $adjuster->name}}" required>
                  </div>
                  <div class="form-group">
                    <label>NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" autocomplete="off" value="{{ $adjuster->nik}}" required>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" value="{{ $adjuster->email}}" required>
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" autocomplete="off" value="{{ $adjuster->phone}}" required>
                  </div>
                  <div class="form-group">
                    <label>Positions</label>
                    <select class="form-control" name="positions">
                      @foreach ( $master_positions as $key => $value )
                        <option value="{{ $value->id }}" {{ $array_position_id[$adjuster->position_id]}}>{{ $value->position_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                      <label>File input</label>
                      <input type="file" id="thumbnail" name="thumbnail">
                  </div>
                  <div class="form-group">
                    <label>Set Active</label>
                    @if ( $adjuster->deleted_at == "")
                      <input type="checkbox" name="is_active" checked>
                      <span class="label label-success">Active</span>
                    @else
                      <input type="checkbox" name="is_active">
                      <span class="label label-danger">Not Active</span>
                    @endif
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a class="btn btn-danger" href="{{ url('/')}}/master/adjusters">Back</a>
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
