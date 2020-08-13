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
              <h3 class="box-title">Master Division</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <form role="form" method="post" name="form1" action="{{ url('/')}}/master/division/store">
                  <input type="hidden" name="division_id" id="division_id">
                  {{ csrf_field() }}
                  <!-- text input -->
                  <div class="form-group">
                    <label>Add Division</label>
                    <input type="text" class="form-control" autocomplete="off" name="division" id="division" required>
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
                  <th>Insurance</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ( $master_division as $key => $value )
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $value->division_name}}</td>
                      <td><button class="btn btn-info" onClick="viewDetail('{{$value->id}}','{{$value->division_name}}')">Detail</button></td>
                    </tr>
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
@include("master::division.footer");
</body>
</html>
