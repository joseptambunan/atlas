<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("adjuster::header")
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
        User Profile
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{url('/')}}/assets/images.png" alt="User profile picture">
              <h3 class="profile-username text-center">{{ $adjuster_data->name }}</h3>
              <p class="text-muted text-center">Adjuster</p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- Profile Image -->
          <div class="box box-primary">
            <center>Todo List</center>
            <!-- /.box-body -->
            <div class="box-body" id="box_todo_list">
              <h4>Loading...</h4>
            </div>
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">IOU & Expenses</a></li>
              <li><a href="#timeline" data-toggle="tab">Case</a></li>
              <li><a href="#settings" data-toggle="tab">Profile Data</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                @if ( ( $limit_balance->value - $iou_not_complete ) > 0 )
                  <a href="{{ url('/')}}/adjuster/iou/add" class="btn btn-primary">Request IOU</a>
                @endif
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal-default">Create Expenses</a>
                <span style="font-size: 14px;font-weight: bolder;">Balance : Rp. {{ number_format($limit_balance->value - $iou_not_complete ) }}</span>
                <center><h5>IOU List</h5></center>
                <table class="table table-bordered table-hover">
                  <thead class="header_background">
                    <tr>
                      <td>No.</td>
                      <td>Case Number</td>
                      <td>Title</td>
                      <td>Status</td>
                      <td>Total IOU</td>
                      <td>Total Expenses</td>
                      <td>Detail</td>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i=0; @endphp
                    @foreach ( $adjuster_data->ious as $key => $value )
                      @if ( $value->deleted_at == "" )
                        <tr>
                          <td>{{ $i + 1 }}</td>
                          <td>
                            <ul>
                              @foreach ( $value->cases as $key_cases => $value_cases )
                                <li>{{ $value_cases->adjuster_casenumber->case->case_number }}</li>
                              @endforeach
                            </ul>
                          </td>
                          <td>{{ $value->title }}</td>
                          <td><span class="{{ $value->status['class']}}">{{ $value->status['label'] }}</span></td>
                          <td>Rp. {{ number_format($value->total)}}</td>
                          <td>Rp. {{ number_format($value->total_expenses)}}</td>
                          <td><a class="btn btn-info" href="{{ url('/')}}/adjuster/iou/show/{{$value->id }}">Detail</a></td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <table class="table table-bordered table-hover">
                  <thead class="header_background">
                    <tr>
                      <td>No.</td>
                      <td>Case Number</td>
                      <td>Title</td>
                      <td>Status</td>
                      <td>Detail</td>
                    </tr>
                  </thead>
                  
                  <tbody>
                    @php $i=0; @endphp
                    @foreach ( $adjuster_data->cases as $key => $value )
                      @if ( $value->deleted_at == "")
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $value->case->case_number }}</td>
                        <td>{{ $value->case->title }}</td>
                        <td><span class="label label-success">In Progress</span></td>
                        <td><a href="{{ url('/')}}/adjuster/case/show/{{ $value->case->id }}" class="btn btn-warning">Detail</a></td>
                      </tr>
                      @php $i++; @endphp
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <form class="form-horizontal" action="{{ url('/')}}/adjuster/update/data" method="post" name="form">
                  {{ csrf_field() }}
                  <input type="hidden" name="adjuster_id" id="adjuster_id" value="{{ $adjuster_data->id}}">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="name" name="name" value="{{$adjuster_data->name}}" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="email" name="email" value="{{$adjuster_data->email}}" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Phone</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="phone" name="phone" value="{{ $adjuster_data->phone}}" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="password" name="password" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Jabatan</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="jabtan" name="jabtan" value="{{ $adjuster_data->position->position_name}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
 @include ("copyright")

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  <form method="post" enctype="multipart/form-data" id="upload_expenses">
    {{ csrf_field() }}
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Detail Expenses</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>IOU</label>
              <select name="iou_number" id="iou_number" class="form-control" required>
                <option value="">&nbsp;</option>
              </select>
            </div>
            <div class="form-group">
              <label>Case</label>
              <select name="iou_list_id" id="iou_list_id" class="form-control" required>
              @foreach ( $adjuster_data->cases as $key => $value )
                <option value="{{ $value->case->id}}">{{ $value->case->case_number }}</option>
              @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Type</label>
              <input type="text" name="type_expenses" id="type_expenses" class="form-control" autocomplete="off" required> 
            </div>
            <div class="form-group">
              <label>Ammount</label>
              <input type="text" name="ammount_expenses" id="ammount_expenses" class="form-control" autocomplete="off" required>
            </div>
            <div class="form-group">
              <label>Description</label>
              <input type="text" name="description" id="description" class="form-control" autocomplete="off" required>
            </div>
            <div class="form-group">
              <label>Receipt</label>
              <input type="file" name="receipt" id="receipt" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_expenses">Save changes</button>
            <span id="loading" style="display: none;">Loading...</span>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </form>
</div>
<!-- ./wrapper -->
@include("adjuster::footer")
<script type="text/javascript">
  $("#ammount_expenses").number(true);
</script>
</body>
</html>
