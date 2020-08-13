<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::adjuster.header")
  <!-- Select2 -->
  <link rel="stylesheet" href="{{url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
  
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
        <div class="col-xs-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Case</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/casenumbers/create">
                {{ csrf_field() }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Casenumber</label>
                    <input type="text" class="form-control" id="casenumber" name="casenumber" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" id="title" name="title" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Insurance Client</label>
                    <select class="form-control select2" name="insurance">
                      @foreach ( $master_insurance as $key => $value )
                      <option value="{{ $value->id}}">{{ $value->insurance_name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Division</label>
                    <select class="form-control" name="division">
                      @foreach ( $master_division as $key => $value )
                      <option value="{{ $value->id}}">{{ $value->division_name }}</option>
                      @endforeach
                    </select>
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
@include("casenumbers::footer");
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
