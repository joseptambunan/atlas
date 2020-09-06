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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Expenses</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form method="post" enctype="multipart/form-data" action="{{url('/')}}/casenumbers/expenses/store">
                {{ csrf_field() }}
                <div class="modal-body">
                  <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type_expenses" id="type_expenses" class="form-control ammount" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Ammount</label>
                    <input type="text" name="ammount_expenses" id="ammount_expenses" class="form-control" autocomplete="off" onkeyup="setCalculate();" required>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" id="description" class="form-control" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <label>Receipt</label>
                    <input type="file" name="receipt" id="receipt" class="form-control">
                  </div>
                  
                  <div class="form-group">
                    @if ( count($master_casenumbers) > 0 )
                      <button type="submit" class="btn btn-primary">Submit</button>
                      <span id="loading" style="display: none;">Loading...</span>
                    @endif
                    <a href="{{url('/')}}/casenumbers/index" class="btn btn-warning">Back</a>
                  </div>
                  <div class="form-group">
                     <table id="example4" class="table table-bordered table-hover">
                      <thead class="header_background">
                      <tr>
                        <th>No.</th>
                        <th>Case Number</th>
                        <th>Title</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Set to Expenses</th>
                        <th>Set value</th>
                      </tr>
                      </thead>
                      <tbody>
                        @php $i=0; @endphp
                        @foreach ( $master_casenumbers as $key => $value )
                          @if ( $value->deleted_at == "")
                          <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $value->case_number}}</td>
                            <td>{{ $value->title}}</td>
                            <td>{{ date("d-M-Y", strtotime($value->created_at)) }}</td>
                            <td>{{ $value->created }}</td>
                            <td><input type="checkbox" name="case[]" value="{{$value->id}}" class="calculate" onClick="setCalculate();" ></td>
                            <td>
                              <input type="text" id="expenses_{{$value->id}}" name="expenses[]" class="form-control calculate_subtotal">
                              <input type="hidden" id="case_id" name="case_id[]" value="{{ $value->id}}" class="form-control calculate_subtotal">
                            </td>
                          </tr>
                          @php $i++;@endphp
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                  </div>
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
@include("casenumbers::footer");
</body>
</html>
