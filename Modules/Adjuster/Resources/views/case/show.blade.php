<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::adjuster.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<input type="hidden" name="expenses" id="expenses" value="{{$expenses}}">
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
                  @if ( isset($casenumber->invoice))
                    @if ( $finish_status == "" )
                      <p>This case have Invoice Number. Please confirm to finish this case</p>
                      <button type="button" class="btn btn-success" onClick="finishCase('{{ $casenumber->invoice->id }}')">Finish</button>
                    @else
                      <label class="label label-info">Finish by Adjuster</label>
                    @endif
                  @endif
                  @if ( $casenumber->deleted_at == "" && $finish_status == "")
                    <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal-default">Create Expenses</a>
                  @endif
                  <a class="btn btn-warning" href="{{ url('/')}}/adjuster/index/">Back</a>
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
                        <form action="{{url('/')}}/adjuster/case/approval" method="post" name="form1">
                          {{ csrf_field() }}
                          <input type="hidden" name="case_show" value="{{ $casenumber->id}}">
                          <button type="submit" class="btn btn-success">Request Approve</button>
                          <h4>Expenses List</h4>
                          <h4>Total : Rp. {{ number_format($casenumber->total_expenses)}}</h4>
                          <table id="example4" class="table table-bordered table-hover">
                            <thead class="header_background">
                            <tr>
                              <th>No.</th>
                              <th>Type</th>
                              <th>Ammount</th>
                              <th>Description</th>
                              <th>Created at</th>
                              <th>Created by</th>
                              <th>Request Approve</th>
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
                                <td>
                                  @if ( $value->status_approval($user->id)['status'] == 0 || $value->status_approval($user->id)['status'] == 2 )
                                  <input type="checkbox" name="checklist[]" value="{{ $value->id}}">
                                  @endif
                                </td>
                                <td><span class="{{ $value->status_approval($user->id)['class']}}">{{ $value->status_approval($user->id)['label']}}</span></td>
                                <td>
                                  @if ( $value->iou_lists_id != "")
                                    {{ $value->iou_lists->iou->title }}
                                  @endif
                                </td>
                                <td>
                                   @if ( $value->iou_lists_id != "")
                                  <a href="{{ url('/')}}/adjuster/iou/show/{{ $value->iou_lists->iou->id }}" class="btn btn-primary">Detail</a>
                                  @endif

                                  @if ( $value->status_approval($user->id)['status'] == 0 )
                                      <button class="btn btn-sm btn-danger" onClick="removeDataExpenses('{{ $value->id}}')">Remove Detail</button>
                                  @endif

                                  @if ( $value->status_approval($user->id)['status'] == 2 )
                                      <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-revisi" onClick="setRevisi('{{$value->id}}')" type="button">Revisi</button>
                                  @endif
                                </td>
                              </tr>
                              @php $i++; @endphp
                              @endforeach
                            </tbody>
                          </table>
                        </form>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <h4>IOU List</h4>
                        <h4>Total IOU: Rp. {{ number_format($casenumber->total_iou_planned)}}</h4>
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
                                  <td><a href="{{ url('/')}}/adjuster/iou/show/{{$value_ious->iou->id}}" class="btn btn-warning">Detail</a></td>
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
                          <li>{{ $value->adjuster->name }} @if ( $value->updated_by != "" ) <i>Finish at</i> {{ date('d-M-Y', strtotime($value->updated_at))}} @endif</li>
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
  <form method="post" enctype="multipart/form-data" id="upload_expenses">
    {{ csrf_field() }}
    <input type="hidden" name="iou_list_id" id="iou_list_id" value="{{ $casenumber->id}}">
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
  <!-- /.modal -->
</div>
<!-- ./wrapper -->
@include("master::document.footer");
<!-- bootstrap datepicker -->
<script src="{{url('/')}}/assets/plugins/customd-jquery-number-c19aa59/jquery.number.min.js"></script>
<script type="text/javascript">
   $( document ).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
        });

      $("#btn_expenses").click(function(){
        $("#btn_expenses").hide();
        $("#loading").show();
        saveExpenses();
      });

      $("#ammount_expenses").number(true);
    });

   $('#example4').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });

   function cancelthiscase(id,status){
    if ( confirm("Are you sure to update this case ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/casenumbers/delete",
        dataType : "json",
        data : {
          id : id,
          status: status
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == 0 ){
          alert("Case has been updated");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
   }

   function submitInvoice(){
    if ( confirm("Are you sure to create invoice ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/casenumbers/invoice/create",
        dataType : "json",
        data :{
          invoice_number : $("#invoice_number").val(),
          case_id : $("#casenumber_id").val()
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == "0"){
          alert("Invoice has been created");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
   }

   function finishCase(id){
    if ( $("#expenses").val() == 0 ){
      alert("You expenses not exist or not approve. Please check before");
      return false;
    }

    if ( confirm("Are you sure to finish this case ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/adjuster/invoice/finish",
        dataType : "json",
        data : {
          id : id
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == "0"){
          alert("Case has been finish");
        }else{
          alert("You expenses not exist. Please check before");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
   }

   function saveExpenses(){
    var data = new FormData();
    //Form data
    var form_data = $('#upload_expenses').serializeArray();
    $.each(form_data, function (key, input) {
        data.append(input.name, input.value);
    });

    //File data
    var file_data = $('input[name="receipt"]')[0].files;

    for (var i = 0; i < file_data.length; i++) {
        data.append("receipt", file_data[i]);
    }


    var request = $.ajax({
      url : "{{url('/')}}/adjuster/case/expenses",
      dataType : "json",
      data :data,
      type : "post",
            enctype: 'multipart/form-data',
            contentType : false,
            processData: false
    });

    request.done(function(data){
      if ( data.status == "0"){
        alert("Expenses has been created");
      }

      window.location.reload();
    });
   }
</script>
</body>
</html>
