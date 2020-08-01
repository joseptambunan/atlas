<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("adjuster::iou.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("adjuster::navbar")
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
              <h3 class="box-title">Add IOU</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/adjuster/iou/store">
                {{ csrf_field() }}
                <input type="hidden" name="adjuster_id" value="{{$adjuster_data->id}}">
                <div class="box-body">
                  <div class="form-group">
                    <label>Case Number</label>
                    <select class="form-control select2" multiple="multiple" style="width: 100%;" name="case_id[]">
                      @foreach ( $adjuster_data->cases as $key => $value )
                      <option value="{{ $value->id}}">{{ $value->case->case_number}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" id="title" name="title" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Client</label>
                    <input type="text" class="form-control" id="client" name="client" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Division</label>
                    <input type="text" class="form-control" id="division" name="division" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Type of Survey</label>
                    <input type="text" class="form-control" id="tos" name="tos" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Location</label>
                    <input type="text" class="form-control" id="location" name="location" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Periode Date</label><br/>
                    <div class="col-xs-6">
                      <input type="text" class="form-control pull-right" id="datepicker_start" name="datepicker_start" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                      <input type="text" class="form-control pull-right" id="datepicker_end" name="datepicker_end" autocomplete="off">
                    </div>
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
@include("adjuster::iou.footer");
</body>
</html>
