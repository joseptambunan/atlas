<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("approval::invoice.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  {{ csrf_field() }}
  @include ("approval::invoice.navbar")
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
              <h3 class="box-title">Master IOU</h3>
              <a href="{{ url('/')}}/adjuster/index" class="btn btn-warning">Back</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example4" class="table table-bordered table-hover">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Case Number</th>
                  <th>Created at</th>
                  <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                  @php $i=0; @endphp
                  @foreach ( $adjuster_data->pending_invoice as $key => $value )
              
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ $value['case_number'] }}</td>
                      <td>{{ $value['created_at']}}</td>
                      <td><a class="btn btn-success" href="{{ url('/')}}/approval/invoice/show/{{$value['id']}}">Detail</a></td>
                    </tr>
                    @php $i++ @endphp
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
@include("approval::invoice.footer");
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
