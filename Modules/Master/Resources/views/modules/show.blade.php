<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::modules.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("master::modules.navbar")
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
              <h3 class="box-title">Master Document</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6">
                <form role="form" method="post" name="form1" action="{{ url('/')}}/master/document/update">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $master_document->id }}">
                  <!-- text input -->
                  <div class="form-group">
                    <label>Add Document</label>
                    <input type="text" class="form-control" autocomplete="off" name="document" value="{{ $master_document->document }}" required>
                  </div>
                  <div class="form-group">
                    <label>Active</label>
                    <input type="checkbox" name="active" {{ $checked }}>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-success" type="submit">Update</button>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default">
                      Add Approval
                    </button>
                    <a href="{{ url('/')}}/master/document" class="btn btn-danger">Back</a>
                  </div>
                </form>
              </div>
            
              <table id="example2" class="table table-bordered table-hover">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Jabatan</th>
                  <th>Level Approval</th>
                  <th>Edit</th>
                </tr>
                </thead>
               <tbody>
                 @foreach ( $master_document->approvals as $key => $value )
                 @php $value->deleted_at == "" @endphp
                 <tr>
                  <td>{{ $start + 1 }}</td>
                  <td>{{ $value->jabatan_approvals->jabatan->position_name}}</td>
                  <td>{{ $value->level }}</td>
                 </tr>
                 @endforeach
               </tbody>
              </table>
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
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Tambah Approval</h4>
        </div>
        <div class="modal-body">
          @if ( ( count($master_position) - 1 ) <= 0 )
            <p>(No Management Levels Available)</p>
          @else
            <form role="form" method="post" name="form1" action="{{ url('/')}}/master/document/approval">
              {{ csrf_field() }}
              <input type="hidden" name="id" value="{{ $master_document->id }}">
              <!-- text input -->
              <div class="form-group">
                <label>Position</label>
                <select class="form-control" name="position">
                  @foreach ( $master_position as $key => $value )
                    @if ( $value->id != 1)
                      <option value="{{ $value->id}}">{{ $value->position_name }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Level ( sort from high to low level )</label>
                <select class="form-control" name="level">
                  @for ( $i=1; $i <= (count($master_position) - 1 ); $i++ )
                  <option value="{{ $i}}">Level {{ $i }}</option>
                  @endfor
                </select>
              </div>
              <div class="form-group">
                <button class="btn btn-success" type="submit">Simpan</button>
              </div>
            </form>
          @endif
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
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@include("master::document.footer");
</body>
</html>
