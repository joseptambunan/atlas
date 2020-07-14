<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::position.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("master::position.navbar")
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
        <div class="col-xs-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Master Position</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <form role="form" method="post" name="form1" action="{{ url('/')}}/master/position/create">
                  {{ csrf_field() }}
                  <!-- text input -->
                  <div class="form-group">
                    <label>Add Position</label>
                    <input type="text" class="form-control" autocomplete="off" name="position" required>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-success" type="submit">Add</button>
                  </div>
                </form>
              </div>
            
              <table id="example2" class="table table-bordered table-hover">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Nama Document</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ( $master_position as $key => $value )
                    @if ( $value->id != 1)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->position_name}}</td>
                        <td><a class="btn btn-info" href="{{ url('/')}}/master/position/show/{{$value->id}}">Detail</a></td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
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
</body>
</html>
