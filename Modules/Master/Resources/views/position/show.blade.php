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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Master Position</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6">
                <form role="form" method="post" name="form1" action="{{ url('/')}}/master/position/update">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $position->id }}">
                  <!-- text input -->
                  <div class="form-group">
                    <label>Position</label>
                    <input type="text" class="form-control" autocomplete="off" name="position" value="{{ $position->position_name }}" required>
                  </div>
                  <div class="form-group">
                    <label>Active</label>
                    <input type="checkbox" name="active" {{ $checked }}>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-success" type="submit">Update</button>
                    <a href="{{ url('/')}}/master/position" class="btn btn-danger">Back</a>
                  </div>
                </form>
              </div>  
              
              <div class="col-md-12 box-body table-responsive no-padding">
              <table id="example2" class="table table-bordered table-hover table-responsive">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Document</th>
                  <th>Level Approval</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ( $position->approval as $key => $value )
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->approval_document->document->document }}</td>
                    <td>{{ $value->approval_document->level }}</td>
                    <td><a href="{{ url('/')}}/master/document/show/{{$value->approval_document->document->id}}" class="btn btn-warning" target="_blank">Edit</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              </div>

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
