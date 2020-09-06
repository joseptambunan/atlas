<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::adjuster.header")
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
              <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/casenumbers/update">
                <input type="hidden" value="{{ $casenumber->id}}" name="casenumber_id" id="casenumber_id">
                {{ csrf_field() }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Case number</label>
                    <input type="text" class="form-control" id="casenumber" name="casenumber" value="{{ $casenumber->case_number }}" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $casenumber->title }}" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Insurance Client</label>
                    <select class="form-control" name="insurance">
                      @foreach ( $master_insurance as $key => $value )
                        @if ( $casenumber->insurance_id == $value->id )
                          <option value="{{ $value->id}}" selected>{{ $value->insurance_name }}</option>
                        @else
                          <option value="{{ $value->id}}">{{ $value->insurance_name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Division</label>
                    <select class="form-control" name="division">
                      @foreach ( $master_division as $key => $value )
                        @if ( $casenumber->insurance_id == $value->id )
                          <option value="{{ $value->id}}" selected>{{ $value->division_name }}</option>
                        @else
                          <option value="{{ $value->id}}">{{ $value->division_name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Total IOU Planned</label>
                    <input type="text" class="form-control" id="total_iou_planned" name="total_iou_planned" value="{{ number_format($casenumber->total_iou_planned) }}" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Total Expenses</label>
                    <input type="text" class="form-control" id="total_expenses" name="total_expenses" value="{{ number_format($casenumber->total_expenses) }}" autocomplete="off" required>
                  </div>
                  @if ( $casenumber->invoice)
                  <div class="form-group">
                    <label>Invoice Number</label>
                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ $casenumber->invoice->invoice_number }}" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Bukti Pengembalian</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ $casenumber->description }}" autocomplete="off" required>
                  </div>
                  @endif

                  <div class="form-group">
                    
                    <div class="col-md-6">
                      Status : 
                      @if ( $casenumber->deleted_at == "")
                      <span class="label label-success">Active</span>
                      @else
                      <span class="label label-danger">Non Active</span>
                      @endif

                      <h5>Total IOU : <span>{{ $casenumber->total_iou['total'] }}</span></h5>
                      <h5>Complete: <span class="label label-success">{{ $casenumber->total_iou['expenses_complete']}}</span></h5>
                      <h5>In Complete: <span class="label label-danger">{{ $casenumber->total_iou['in_progress']}}</span></h5>
                    </div>
                    <div class="col-md-6">
                      @if ( $casenumber->invoice_number != "" && $casenumber->total_iou['in_progress'] > 0 )
                      <div class="col-md-6 alert alert-danger">
                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                        This Case has been IOU not completed
                      </div>
                      @endif
                    </div> 
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  @if ( $casenumber->invoice_number == "")
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn {{$class[$status]['button']}}" onClick="cancelthiscase('{{$casenumber->id}}','{{ $status}}');">Update to {{ $status }} this case</button>
                  @endif
                  @if ( ($casenumber->total_iou['total']) > 0 && $casenumber->invoice_number == "" )
                   <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#modal-default">Create Invoice</button>
                  @endif
                  <a class="btn btn-warning" href="{{ url('/')}}/casenumbers/">Back</a>
                  <a class="btn btn-info" href="{{ url('/')}}/casenumbers/download/{{$casenumber->id}}">Download as Excel</a>
                  @if ( $casenumber->invoice)
                   <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#modal-pengembalian">Input Return</button>
                  @endif
                </div>
              </form>
            
              <div class="col-md-12">
                <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">Expenses</a></li>
                      <li><a href="#tab_2" data-toggle="tab">IOU</a></li>
                      <li><a href="#tab_3" data-toggle="tab">Adjuster</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1">

                        <h4>Expenses List</h4>
                        <h4>Total Expenses : Rp.{{ number_format($casenumber->total_expenses)}}</h4>
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Ammount</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>Status Approval</th>
                            <th>Action</th>
                            <th>IOU Reference</th>
                            <th>Receipt</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach ( $casenumber->case_expenses as $key => $value )
                            <tr>
                              <td>
                                {{ $key + 1 }}
                                <input type="hidden" id="reference_id_{{$value->id}}" value="{{ $value->id}}">
                                <input type="hidden" id="reference_type_{{$value->id}}" value="{{ $value->type}}">
                                <input type="hidden" id="reference_ammount_{{$value->id}}" value="{{ $value->ammount}}">
                                <input type="hidden" id="reference_desc_{{$value->id}}" value="{{ $value->description}}">
                              </td>
                              <td>{{ $value->type }}</td>
                              <td>Rp.{{ number_format($value->ammount) }}</td>
                              <td>{{ date('d-M-Y', strtotime($value->created_at))}}</td>
                              <td>{{ $value->created->adjusters->name }}</td>
                              <td><span class="{{ $value->status['class']}}">{{ $value->status['label']}}</span></td>
                              <td>
                                @if ( $value->status['status'] == "2" || $value->status['status'] == "0" )
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-revisi" onClick="setRevisi('{{$value->id}}')" type="button">Revisi</button>
                                @endif
                              </td>
                              <td>
                                @if ( $value->iou_lists_id != "")
                                  <a href="{{url('/')}}/casenumbers/iou/show/{{ $value->iou_lists->iou->id }}" target="_blank" class="btn btn-info">{{ $value->iou_lists->iou->title }}</a>
                                @endif
                              </td>
                              <td>
                                @if ( $value->receipt != "" )
                                  <a href="{{ url('/')}}/approval/download/{{$value->id}}">Download Receipt</a>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <h4>IOU List</h4>
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Ammount</th>
                            <th>Total Expenses</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>Status Approval</th>
                            <th>Detail</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php $i=0; @endphp
                            @foreach ( $casenumber->adjusters as $key => $value )
                              @foreach ( $value->ious as $key_iou => $value_ious )
                                @if ( $value_ious->iou )
                                  @if ( $value_ious->iou->deleted_at == "")
                                  <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $value_ious->iou->type_of_survey }}</td>
                                    <td>Rp. {{ number_format($value_ious->iou->total) }}</td>
                                    <td>Rp. {{ number_format($value_ious->iou->total_expenses) }}</td>
                                    <td>{{ $value_ious->iou->created->adjusters->name }}</td>
                                    <td>{{ date("d-M-Y", strtotime($value_ious->iou->created_at)) }}</td>
                                    <td><span class="{{ $value_ious->iou->status['class'] }}">{{ $value_ious->iou->status['label'] }}</span></td>
                                    <td><a href="{{ url('/')}}/casenumbers/iou/show/{{$value_ious->iou->id}}" class="btn btn-warning">Detail</a></td>
                                  </tr>
                                  @php $i++; @endphp
                                  @endif
                                @endif
                              @endforeach
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="tab-pane" id="tab_3">
                        <h4>Adjuster</h4>
                        <ul>
                        @foreach ( $casenumber->adjusters as $key => $value )
                          @if ( $value->deleted_at == "")
                            <li> {{ $value->adjuster->name }} @if ( $value->updated_by ) <i>Finish at</i> {{ date('d-M-Y', strtotime($value->updated_at))}} @endif </li>
                          @endif
                        @endforeach
                        </ul>
                        @if ( $casenumber->invoice_number == "")
                        <a href="{{ url('/')}}/casenumbers/adjuster/all/{{$casenumber->id}}" class="btn btn-success">Add Adjuster</a>
                        @endif
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
          <h4 class="modal-title">Invoice</h4>
        </div>
        <div class="modal-body">
          <label>Invoice Number</label>
          <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="" autocomplete="off" required />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onClick="submitInvoice()">Save</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Default Modal</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Type</label>
            <input type="text" class="form-control" id="expenses_type" name="expenses_type" value="" disabled>
          </div>
          <div class="form-group">
            <label>Ammount</label>
            <input type="text" class="form-control" id="expenses_ammount" name="expenses_ammount" value="" disabled>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" cols="30" rows="6" name="expenses_description" id="expenses_description" disabled></textarea>
          </div>
          <div class="form-group">
            <label>Receipt</label>
            <a href="{{ url('/')}}/approval/download/" id="expenses_receipt" name="expenses_receipt" target="_blank">Download Receipt</a>
          </div>
          <div class="form-group">
            <label>Reason</label>
            <textarea class="form-control" cols="30" rows="6" name="reason" id="reason"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal-pengembalian">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Pengembalian</h4>
        </div>
        <div class="modal-body">
          <label>Bukti Pengembalian</label>
          <input type="text" class="form-control" id="pengembalian" name="pengembalian" value="" autocomplete="off" required />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onClick="submitPengembalian()">Save</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

   <form method="post" enctype="multipart/form-data" action="{{url('/')}}/casenumbers/expenses/update">
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
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn_revisi_expenses">Save changes</button>
          <span id="loading_revisi" style="display: none;">Loading...</span>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  </form>
</div>
<!-- ./wrapper -->
@include("casenumbers::footer");

</body>
</html>
