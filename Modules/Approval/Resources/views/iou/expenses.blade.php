<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("approval::iou.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("approval::iou.navbar")
  @include( "sidebar",['user' => $user, 'config_sidebar' => $config_sidebar] )
  {{ csrf_field()}}
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
              <h3 class="box-title">Detail Expenses</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <a class="btn btn-warning" href="{{ url('/')}}/approval/case/show/{{ $case_expenses->master_casenumbers_id}}">Back</a>
                <input type="hidden" id="expenses_approval_id" value="{{ $approval_detail->id }}">
                <button type="button" class="btn btn-success" onClick="setApprove('{{$approval_detail->id}}')">
                    Approve
                  </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-default">
                  Reject
                </button>
                @if ( $case_expenses->iou_lists_id != "" ) <p>IOU : {{ $case_expenses->iou_lists->iou->title }}</p>@endif
                <p>Ammount : {{ $case_expenses->ammount }}</p>
                <p>Desc : {{ $case_expenses->description}} </p>
                <table id="example4" class="table table-bordered table-hover">
                  <thead class="header_background">
                    <tr>
                      <td>No.</td>
                      <td>Username</td>
                      <td>Status</td>
                      <td>Message</td>
                      <td>Date</td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ( $case_expenses->list_approva->details as $key => $value )
                    <tr>
                      <td>{{ $start + 1 }}</td>
                      <td>{{ strtoupper($value->user_detail->adjusters->name) }}</td>
                      <td><span class="{{ $array_status[$value->status]['class']}}">{{ $array_status[$value->status]['label']}}</span></td>
                      <td>{{ strtoupper($value->description) }}</td>
                      <td>{{date("d/M/Y", strtotime($value->updated_at))}}</td>
                    </tr>
                    @php $start++; @endphp
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
  <!-- /.modal -->
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
            <label>Reason</label>
            <textarea class="form-control" cols="30" rows="6" name="reason" id="reason"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" onClick="approveReject('2')">Reject</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  
</div>
<!-- /.modal -->


</div>
<!-- ./wrapper -->
@include("approval::iou.footer");

</body>
</html>
