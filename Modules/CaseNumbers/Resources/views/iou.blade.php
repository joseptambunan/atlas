<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::case.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  {{ csrf_field() }}
  @include ("master::case.navbar")
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
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form method="post" action="{{ url('/')}}/casenumbers/search/iou">
              {{ csrf_field() }}
              <div class="col-md-4">
                <div class="form-group">
                  <label>Search IOU</label>
                </div>
                <div class="form-group">
                  <label>Search By </label>
                  <select class="form-control" name="search_by">
                    <option value="client">Client</option>
                    <option value="title">Title</option>
                    <option value="adjusters">Adjuster</option>
                    <option value="case">Case Name</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Keyword</label>
                  <input type="text" class="form-control" name="keyword" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <button class="btn btn-success" type="submit">Search</button>
                  <a href="{{ url('/')}}/casenumbers/iou/" class="btn btn-info">Reset</a>
                  <a href="{{ url('/')}}/casenumbers/index/" class="btn btn-warning">Back</a>
                </div>
              </div>
              </form>
            </div>

            <div class="box-body">
              <h2>IOU need to be Process</h2>
              <table id="example4" class="table table-bordered table-hover">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Title</th>
                  <th>Client</th>
                  <th>Created at</th>
                  <th>Created by</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                  @php $i=0; @endphp
                  @foreach ( $master_iou as $key => $value )
                    @if ( $value->status['status'] == 3 && $value->document_number == "" )
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $value->title }}</td>
                      <td>{{ $value->client_name->insurance_name}}</td>
                      <td>{{ date("d-M-Y", strtotime($value->created_at)) }}</td>
                      <td>{{ $value->created->adjusters->name }}</td>
                      <td><span class="{{ $value->status['class']}}">{{ $value->status['label']}}</span></td>
                      <td>Rp. {{ number_format($value->total)}}</td>
                      <td><a class="btn btn-info" href="{{ url('/')}}/casenumbers/iou/show/{{$value->id}}">Detail</a></td>
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
