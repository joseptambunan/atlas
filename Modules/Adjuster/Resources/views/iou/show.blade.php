<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("adjuster::iou.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("master::adjuster.navbar")
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
                    <label>Title</label>
                    <input type="text" class="form-control" name="title" value="{{$iou_data->title}}" disabled>
                  </div>
                  <div class="col-md-6">
                    <label>Case Number</label>
                    <select class="form-control select2" multiple="multiple" style="width: 100%;" name="case_id[]">
                      @foreach ( $adjuster_data->cases as $key => $value )
                      <option value="{{ $value->id}}">{{ $value->case->case_number}}</option>
                      @endforeach
                    </select>
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
                    <div class="col-xs-6">
                      <input type="text" class="form-control pull-right" id="datepicker_start" name="datepicker_start">
                    </div>
                    <div class="col-xs-6">
                      <input type="text" class="form-control pull-right" id="datepicker_end" name="datepicker_end">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label>Total</label>
                    <h4>Rp. {{ number_format($iou_data->total)}}</h4>
                    @if ( $iou_data->status['status'] == 2 || $iou_data->status['status'] == 3)
                      <span class="{{ $iou_data->status['class']}}">{{ $iou_data->status['label']}}</span>
                    @endif
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  @if ( $check_approval == "" )
                    <button type="button" class="btn btn-info" onClick="requestApproval('{{$iou_data->id}}','{{ $approval_id}}')">Request Approval</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                  
                  @endif
                  <a class="btn btn-warning" href="{{ url('/')}}/adjuster/index/">Back</a>
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
                        @if ( $check_approval == "" )
                          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">Add Detail</button>
                        @endif
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                            <tr>
                              <td>No.</td>
                              <td>Type</td>
                              <td>Ammount</td>
                              <td>Description</td>
                              <td>Delete</td>
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
                                    <td>
                                      @if ( $check_approval == "" )
                                      <button class="btn btn-sm btn-danger" onclick="removeData('{{$value->id}}')">Delete</button>
                                      @endif
                                    </td>
                                  </tr>
                                @php $i++; @endphp
                              @endforeach
                          </tbody>
                        </table>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <h4>Expenses List</h4>
                        <form method="post" name="form1" action="{{ url('/')}}/approval/request_approval">
                          {{ csrf_field() }}
                          <input type="hidden" name="document_id" value="{{ $iou_data->id}}">
                          <input type="hidden" name="document_type" value="2">
                          <button type="submit" class="btn btn-success">Request Approve</button>
                          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-detail">Create Expenses</button>
                          <table id="example4" class="table table-bordered table-hover">
                            <thead class="header_background">
                              <tr>
                                <td>No.</td>
                                <td>Type</td>
                                <td>Ammount</td>
                                <td>Description</td>
                                <td>Status</td>
                                <td>Action</td>
                              </tr>
                            </thead>
                            <tbody>
                              @php $i=0; @endphp
                              @foreach ( $iou_data->cases as $key => $value )
                                @foreach ( $value->expenses as $key_expenses => $value_expenses )
                                <tr>
                                  <td>
                                    {{ $i + 1 }}
                                    <input type="hidden" id="reference_id_{{$value_expenses->id}}" value="{{ $value_expenses->id}}">
                                    <input type="hidden" id="reference_type_{{$value_expenses->id}}" value="{{ $value_expenses->type}}">
                                    <input type="hidden" id="reference_ammount_{{$value_expenses->id}}" value="{{ $value_expenses->ammount}}">
                                    <input type="hidden" id="reference_desc_{{$value_expenses->id}}" value="{{ $value_expenses->description}}">
                                  </td>
                                  <td>{{ $value_expenses->type}}</td>
                                  <td>{{ number_format($value_expenses->ammount)}}</td>
                                  <td>{{ $value_expenses->description}}</td>
                                  <td><span class="{{ $value_expenses->status_approval($user->id)['class']}}">{{ $value_expenses->status_approval($user->id)['label']}}</span></td>
                                  <td>
                                    @if ( $value_expenses->status_approval($user->id)['status'] == 0 )
                                    <input type="checkbox" name="checklist[]" value="{{ $value_expenses->id}}">
                                    <button class="btn btn-sm btn-danger" onClick="removeDataExpenses('{{ $value_expenses->id}}')">Remove Detail</button>
                                    @endif

                                    @if ( $value_expenses->status_approval($user->id)['status'] == 2 )
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-revisi" onClick="setRevisi('{{$value_expenses->id}}')" type="button">Revisi</button>
                                    @endif
                                  </td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                              @endforeach
                            </tbody>
                          </table>
                        </form>

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
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Input Detail</h4>
        </div>
        <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/adjuster/iou/savedetail">
          <div class="modal-body">
              {{ csrf_field() }}
              <input type="hidden" name="iou_id" value="{{$iou_data->id}}">
              <div class="box-body">
                <div class="form-group">
                  <label>Type</label>
                  <input type="text" class="form-control" id="type" name="type" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label>Ammount</label>
                  <input type="text" class="form-control" id="ammount" name="ammount" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <input type="text" class="form-control" id="desc" name="desc" autocomplete="off" required>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <form method="post" enctype="multipart/form-data" id="upload_expenses">
    {{ csrf_field() }}
    <div class="modal fade" id="modal-detail">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Detail Expenses</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Case</label>
              <select name="iou_list_id" id="iou_list_id" class="form-control" required>
                @foreach ( $iou_data->cases as $key => $value )
                  @if ( $value->adjuster_casenumber->case->invoice_number == "" )
                    <option value="{{ $value->id}}">{{ $value->adjuster_casenumber->case->title}}</option>
                  @else
                    @if ( $value->adjuster_casenumber->case->invoice->updated_by == "")
                      <option value="{{ $value->id}}">{{ $value->adjuster_casenumber->case->title}}</option>
                    @endif
                  @endif
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


  <form method="post" enctype="multipart/form-data" id="revisi_expenses">
  <div class="modal fade" id="modal-revisi">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Input Detail</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input type="hidden" name="expenses_id" id="expenses_id">
            <div class="box-body">
              <div class="form-group">
                <label>Type</label>
                <input type="text" class="form-control" id="type_revisi" name="type_revisi" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Ammount</label>
                <input type="text" class="form-control" id="ammount_revisi" name="ammount_revisi" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" id="desc_revisi" name="desc_revisi" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Receipt</label>
                <input type="file" class="form-control" id="receipt_revisi" name="receipt_revisi" autocomplete="off" required>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btn_revisi_expenses">Save changes</button>
          <span id="loading_revisi" style="display: none;">Loading...</span>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  </form>
  <!-- /.modal -->
</div>
<!-- ./wrapper -->
@include("adjuster::iou.footer");

</body>
</html>
