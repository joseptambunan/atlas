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
              <h3 class="box-title">Master User</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
            
              <table id="example3" class="table table-bordered table-hover">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ( $list_user as $key => $value )
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $value->name}}</td>
                      <td>{{ $value->email }}
                      <td><a class="btn btn-primary" href="{{ url('/')}}/setting/user/show/{{$value->id}}">Detail</a></td>
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
@include("setting::user.footer");
</body>
</html>
