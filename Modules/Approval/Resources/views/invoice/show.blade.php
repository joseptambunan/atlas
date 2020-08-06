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
                  
                  <a class="btn btn-warning" href="{{ url('/')}}/adjuster/index/">Back</a>
                </div>
            
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
                        <table id="example4" class="table table-bordered table-hover">
                          <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Ammount</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>Status Approval</th>
                            <th>IOU Reference</th>
                            <th>Detail</th>
                          </tr>
                          </thead>
                            <tbody>
                            @php $i=0; @endphp
                            @foreach ( $casenumber->case_expenses as $key => $value )
                            <tr>
                              <td>{{ $i+1 }}</td>
                              <td>{{ $value->type }}</td>
                              <td>{{ $value->ammount }}</td>
                              <td>{{ $value->description }}</td>
                              <td>{{ date('d-M-Y',strtotime($value->created_at))}}</td>
                              <td>{{ $value->created->adjusters->name }}</td>
                              <td><span class="{{ $value->status['class']}}">{{ $value->status['label']}}</span></td>
                              <td>{{ $value->iou_lists->iou->title }}</td>
                              <td><a href="{{ url('/')}}/adjuster/iou/show/{{ $value->iou_lists->iou->id }}" class="btn btn-primary">Detail</a></td>
                            </tr>
                            @php $i++; @endphp
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
                                @if ( $value_ious->iou->deleted_at == "")
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $value_ious->iou->type_of_survey }}</td>
                                  <td>Rp. {{ number_format($value_ious->iou->total) }}</td>
                                  <td>{{ $value_ious->iou->created->adjusters->name }}</td>
                                  <td>{{ date("d-M-Y", strtotime($value_ious->iou->created_at)) }}</td>
                                  <td><span class="{{ $value_ious->iou->status['class'] }}">{{ $value_ious->iou->status['label'] }}</span></td>
                                  <td><a href="{{ url('/')}}/approval/iou/show/{{$value_ious->iou->id}}" target="_blank" class="btn btn-warning">Detail</a></td>
                                </tr>
                                @php $i++; @endphp
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
                          <li>{{ $value->adjuster->name }} @if ( $value->updated_by != "" ) <strong>Finish at {{ date("d-M-Y",strtotime($value->updated_at)) }} @endif</strong></li>
                          @endif
                        @endforeach
                        </ul>
                        <a href="{{ url('/')}}/casenumbers/adjuster/all/{{$casenumber->id}}" class="btn btn-success">Add Adjuster</a>
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
  
</div>
<!-- ./wrapper -->
@include("master::document.footer");
<script type="text/javascript">
   $( document ).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
        });
    });

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
