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
              <h3 class="box-title">Detail Case</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/adjuster/iou/update">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $iou_data->id}}" name="iou_id">
                <div class="box-body">
                  <div class="col-md-6">
                    <label>Date</label>
                    <input type="text" class="form-control" value="{{date('d-M-Y', strtotime($iou_data->created_at)) }}" disabled>
                  </div>
                  <div class="col-md-6">
                    <label>Adjuster</label>
                    <input type="text" class="form-control" value="{{ $iou_data->created->adjusters->name }}" disabled>
                  </div>
                  <div class="col-md-6">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title" value="{{$iou_data->title}}" disabled>
                  </div>
                  <div class="col-md-6">
                    <label>Client</label>
                    <input type="text" class="form-control" name="client" value="{{$iou_data->client}}">
                  </div>
                  <div class="col-md-6">
                    <label>Division</label>
                    <input type="text" class="form-control" name="division" value="{{$iou_data->division}}">
                  </div>
                  <div class="col-md-6">
                    <label>Type of Survey</label>
                    <input type="text" class="form-control" name="tos" value="{{$iou_data->type_of_survey}}">
                  </div>
                  <div class="col-md-6">
                    <label>Location</label>
                    <input type="text" class="form-control" name="location" value="{{$iou_data->location}}">
                  </div>
                  <div class="col-md-6">
                    <label>Periode Date</label><br/>
                    {{ date("d-M-Y", strtotime($iou_data->starttime))}} - {{ date("d-M-Y", strtotime($iou_data->endtime))}}<br/>
                  </div>
                  <div class="col-md-6">
                    <label>Total</label>
                    <h4>Rp. {{ number_format($iou_data->total)}}</h4>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  @if ( $approval_detail->status == 1)
                  <button type="button" class="btn btn-success" onClick="setApprove('{{$approval_detail->id}}')">
                    Approve
                  </button>
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-default">
                    Reject
                  </button>
                  @else
                  <span class="{{$array_status[$approval_detail->status]['class']}}">{{$array_status[$approval_detail->status]['label']}}</span>
                  @endif

                  <a class="btn btn-warning" href="{{ url('/')}}/{{$start}}">Back</a>

                </div>
              </form>
            
              <div class="col-md-12">
                <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">Planned Expenses</a></li>
                      <li><a href="#tab_2" data-toggle="tab">Expenses</a></li>
                      <li><a href="#tab_3" data-toggle="tab">Approval</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1">
                        <ul>
                          @foreach ( $iou_data->cases as $key_cases => $value_cases )
                            <li>{{ $value_cases->adjuster_casenumber->case->case_number }}</li>
                          @endforeach
                        </ul>
                        <h4>Planned Expenses Detail</h4> 
                       
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                            <tr>
                              <td>No.</td>
                              <td>Type</td>
                              <td>Ammount</td>
                              <td>Description</td>
                            </tr>
                          </thead>
                          <tbody>
                            @php $i=0; @endphp
                              @foreach ( $iou_data->details as $key => $value )
                                  <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>Rp. {{ number_format($value->ammount) }}</td>
                                    <td>{{ $value->description}}</td>
                                  </tr>
                              @endforeach
                          </tbody>
                        </table>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <h4>Expenses List</h4>
                       
                      </div>
                      <div class="tab-pane" id="tab_3">
                        <h4>Approval History</h4>
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                            <tr>
                              <td>No.</td>
                              <td>Name</td>
                              <td>Status</td>
                              <td>Message</td>
                            </tr>
                          </thead>
                          <tbody>
                            @php $i= 0; @endphp
                            @foreach ( $approval_histories as $key => $value )
                            <tr>
                              <td>{{ $i + 1 }}</td>
                              <td>{{ $value['name']}}</td>
                              <td><span class="{{ $value['class']}}">{{ $value['status']}}</span></td>
                              <td>{{ $value['message']}}</td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
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
  <div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ url('/')}}/approval/submit" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="approval_id" id="approval_id" value="{{$approval_detail->id}}">
      <input type="hidden" name="status" id="status" value="2">
      <input type="hidden" name="type" id="type" value="form">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reason</h4>
        <textarea name="description" cols="30" rows="5" required></textarea>
      </div>
      <div class="modal-body">
        <p>Please insert reason why this document rejected</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


</div>
<!-- ./wrapper -->
@include("approval::iou.footer");

</body>
</html>
