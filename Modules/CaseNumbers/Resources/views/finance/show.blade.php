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
                    <label>Total IOU(Rp)</label>
                    <input type="text" class="form-control" value="{{ number_format($casenumber->total_iou_planned) }}" autocomplete="off" disabled>
                  </div>
                  <div class="form-group">
                    <label>Total Reiumberse(Rp)</label>
                    <input type="text" class="form-control" value="{{ number_format($casenumber->total_rembes) }}" autocomplete="off" disabled>
                  </div>
                  <div class="form-group">
                    <label>Total Expenses(Rp)</label>
                    <input type="text" class="form-control" value="{{ number_format($casenumber->total_expenses) }}" autocomplete="off" disabled>
                  </div>
                  <div class="form-group">
                    <label>Selisih(Rp)</label>
                    <input type="text" class="form-control" value="{{ number_format(($casenumber->total_iou_planned + $casenumber->total_rembes ) - $casenumber->total_expenses) }}" autocomplete="off" disabled>
                  </div>
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

                      <a href="{{ url('/')}}" class="btn btn-warning">Back</a>
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

    
            
              <div class="col-md-12">
                <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">Expenses</a></li>
                      <li><a href="#tab_2" data-toggle="tab">IOU</a></li>
                      <li><a href="#tab_4" data-toggle="tab">Reiumberse</a></li>
                      <li><a href="#tab_3" data-toggle="tab">Adjuster</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane table-responsive active" id="tab_1">
                          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info" id="btn_rembes">
                            Create Reiumberse
                          </button>
                          <h4>Expenses List</h4>
                          <h4>Total : Rp. {{ number_format($casenumber->total_expenses)}}</h4>
                          <table id="example3" class="table table-bordered table-hover table-responsive">
                          <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>Status Approval</th>
                            <th>Reimbursement Date</th>
                            <th>Detail</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php $i=0; @endphp
                            @foreach ( $casenumber->case_expenses as $key => $value )
                              <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $value->type }}</td>
                                <td>Rp. {{ number_format($value->ammount) }}</td>
                                <td>{{ $value->description }}</td>
                                <td>{{ date('d-M-Y',strtotime($value->created_at))}}</td>
                                <td>{{ $value->created->adjusters->name }}</td>
                                <td><span class="{{ $value->status['class']}}">{{ $value->status['label']}}</span></td>
                                <td>
                                  @if ( $value->iou_lists_id == "" )
                                    @if ( $value->reimbursement != "" )
                                      {{ date("d/M/Y", strtotime($value->reimbursement->created_at)) }}
                                    @else
                                      @if ( $value->need_rembers == true )
                                      <input type="checkbox" id="rembes_{{$value->id}}" onClick="buildHtml('{{$value->id}}')" value="{{$value->id}}"  data-attribute-desc="{{ $value->description}}" data-attribute-type="{{ $value->type}}" data-attribute-ammount="{{ number_format($value->ammount)}}" data-attribute-created-by="{{ $value->created->adjusters->name }}" data-attribute-created-at="{{ date('d-M-Y',strtotime($value->created_at)) }}" data-attribute-cash="{{ $value->ammount }}">Reiumbersement
                                      @endif
                                    @endif
                                  @endif
                                </td>
                                <td>
                                  @if ( $value->iou_lists_id != "")
                                  <a href="{{ url('/')}}/casenumbers/iou/show/{{ $value->iou_lists->iou->id }}" class="btn btn-primary">IOU : {{ $value->iou_lists->iou->title }}</a>
                                  @endif
                                </td>
                              </tr>
                            @php $i++; @endphp
                            @endforeach
                          </tbody>
                          </table>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane table-responsive" id="tab_2">
                        <h4>IOU List</h4>
                        <h4>Total IOU: Rp. {{ number_format($casenumber->total_iou_planned)}}</h4>
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Amount(Rp)</th>
                            <th>Expenses(Rp)</th>
                            <th>Selisih(Rp)</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>Status Approval</th>
                            <th>Status Return</th>
                            <th>Detail</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php $i=0; @endphp
                            @foreach ( $casenumber->adjusters as $key => $value )
                              @foreach ( $value->ious as $key_iou => $value_ious )

                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $value_ious->iou->type_of_survey }}</td>
                                  <td>Rp. {{ number_format($value_ious->iou->total) }}</td>
                                  <td>Rp. {{ number_format($value_ious->iou->total_expenses) }}</td>
                                  <td>Rp. {{ number_format($value_ious->iou->total - $value_ious->iou->total_expenses) }}</td>
                                  <td>{{ $value_ious->iou->created->adjusters->name }}</td>
                                  <td>{{ date("d-M-Y", strtotime($value_ious->iou->created_at)) }}</td>
                                  <td><span class="{{ $value_ious->iou->status['class'] }}">{{ $value_ious->iou->status['label'] }}</span></td>
                                  <td>
                                    @if ( $value_ious->iou->expenses_approval['total_approval'] == $value_ious->iou->expenses_approval['total_expenses'] )
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default" onClick="upload_return('{{$value_ious->id}}')">Upload Return</button>
                                    @else
                                     <span class="label label-warning">Still any Pending Expenses </span>
                                    @endif

                                    @if ( $value_ious->iou->finish_document != "")
                                      <a href="{{url('/')}}/casenumbers/download_return/{{$value_ious->id}}">Download</a><br/>
                                        at : {{ date("d/m/Y", strtotime($value_ious->iou->finish_at ))}} by {{ $value_ious->iou->user_finish->adjusters->name }}
                                    @else
                                      Return Not Available
                                    @endif
                                  </td>
                                  <td>
                                    
                                    <a href="{{ url('/')}}/casenumbers/iou/show/{{$value_ious->iou->id}}" class="btn btn-warning">Detail</a>
                                  </td>
                                </tr>
                                @php $i++; @endphp
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
                            <li>{{ $value->adjuster->name }} @if ( $value->updated_by != "" ) <i>Finish at</i> {{ date('d-M-Y', strtotime($value->updated_at))}} @endif</li>
                          @endif
                          @endforeach
                        </ul>
                      </div>
                      <div class="tab-pane table-responsive" id="tab_4">
                      
                      <table class="table table-bordered table-hover">
                        <h4>Reiumbersement List</h4>
                        <h4>Total : Rp. {{ number_format($casenumber->total_expenses)}}</h4>
                        <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Created Date</th>
                            <th>Created By </th>
                            <th>Total(Rp)</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ( $casenumber->rembes as $key => $value )
                          <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ date("d/M/Y", strtotime($value->created_at))}}</td>
                            <td>{{ $value->user_transfer->name }}</td>
                            <td>Rp. {{ number_format($value->total) }}</td>
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
  <div class="control-sidebar-bg"></div>
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/casenumbers/iou/set_finish">
        {{ csrf_field() }}
        <input type="hidden" name="iou_id" id="iou_id">
        <input type="hidden" name="case_id" id="case_id" value="{{$casenumber->id}}">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Document Reference</h4>
          </div>
          <div class="modal-body">
            <p>Please input invoice number or transaction slip</p>
            <div class="form-group">
              <label>Receipt</label>
              <input type="file" name="receipt" id="receipt" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal-info">
    <div class="modal-dialog">
      <form role="form" enctype="multipart/form-data" method="post" action="{{ url('/')}}/casenumbers/reiumberse/add">
        {{ csrf_field() }}
        <input type="hidden" name="iou_id" id="iou_id">
        <input type="hidden" name="case_id" id="case_id" value="{{$casenumber->id}}">
        <input type="hidden" name="rembes_total" id="rembes_total" value="0">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reiumberse</h4>
          </div>
          <div class="modal-body">
            <p>Please input invoice number or transaction slip</p>
            <p><strong>Total Rp.<span id="total_rembes"></span></strong></p>
            <div class="form-group">
              <label>Receipt</label>
              <input type="file" name="receipt" id="receipt" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Detail</label>
              <table class="table table-hover table-bordered">
                <thead class="header_background">
                  <th>Type</th>
                  <th>Amount</th>
                  <th>Description</th>
                  <th>Created at</th>
                  <th>Created by</th>
                </thead>
                <tbody id="detail_rembes">
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>
<!-- ./wrapper -->
@include("adjuster::case.footer");
<script type="text/javascript">
  function upload_return(id){
    $("#iou_id").val(id);
  }

  function buildHtml(id){
    var html = "";
    var total = $("#rembes_total").val();
    if ( $("#rembes_" + id). is(":checked")){

      html += "<tr id='element_"+id+"'>";
      html += "<td>" + $("#rembes_" + id).attr("data-attribute-type") + "</td>";
      html += "<td>" + $("#rembes_" + id).attr("data-attribute-ammount") + "</td>";
      html += "<td>" + $("#rembes_" + id).attr("data-attribute-desc") + "</td>";
      html += "<td>" + $("#rembes_" + id).attr("data-attribute-created-at") + "</td>";
      html += "<td>" + $("#rembes_" + id).attr("data-attribute-created-by") + "</td>";
      html += "</tr>";
      html += "<input type='hidden' name='case_rembes_id[]' value='"+ id +"'>";
      $("#detail_rembes").append(html);
      total = parseInt( $("#rembes_" + id).attr("data-attribute-cash")) + parseInt(total) ;
      $("#rembes_total").val(total);
    }else{
      $("#element_" + id).remove();
      total = parseInt(total) - parseInt( $("#rembes_" + id).attr("data-attribute-cash")) ;
    }

    $("#total_rembes").text(total);
    $("#total_rembes").number(true);
  }
</script>
</body>
</html>
