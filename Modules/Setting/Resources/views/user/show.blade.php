<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("setting::user.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("setting::user.navbar")
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
              <h3 class="box-title">Detail User</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6">
                <form role="form" method="post" name="form1" action="{{ url('/')}}/setting/user/update">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $detail_user->id }}">
                  <!-- text input -->
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" autocomplete="off" name="document" value="{{ $detail_user->email }}" required>
                  </div>
                  <div class="form-group">
                    <label>Adjuster</label>
                    <input type="text" class="form-control" autocomplete="off" name="document" value="{{ $detail_user->adjusters->name }}" required>
                  </div>
                  <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" class="form-control" autocomplete="off" name="document" value="{{ $detail_user->adjusters->position->position_name }}" disabled required>
                    <a href="{{url('/')}}/master/adjusters/show/{{$detail_user->adjuster_id}}">Click here to update position name</a>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-success" type="submit">Update</button>
                    <a href="{{ url('/')}}/setting/user" class="btn btn-danger">Back</a>
                  </div>
                </form>
              </div>
              <div class="col-md-6">
                 <h3><strong>Created at : {{ date("d-M-Y", strtotime($detail_user->created_at)) }}</strong> </h3>
                 <h3><strong>Latest Login : {{ date("d-M-Y", strtotime($detail_user->updated_at)) }}</strong> </h3>

              </div>

              <div class="col-md-12">
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">Approval</a></li>
                      <li><a href="#tab_2" data-toggle="tab">Menus</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1">
                        <table id="example2" class="table table-bordered table-hover">
                          <thead class="header_background">
                          <tr>
                            <th>No.</th>
                            <th>Jabatan</th>
                            <th>Document</th>
                            <th>Level Approval</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach ($detail_user->adjusters->position->approval as $key => $value  )
                            <tr>
                              <td>{{ $key + 1 }}</td>
                              <td>{{ $value->approval_document->description }}</td>
                              <td>{{ $value->approval_document->document->document }}</td>
                              <td>{{ $value->approval_document->level }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <form role="form" method="post" name="form1" action="{{ url('/')}}/setting/user/access_module">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $detail_user->id }}">
                            <!-- text input -->
                            <div class="form-group">
                              <label>List Menus</label>
                              <select class="form-control" name="menus">
                                @foreach ( $master_modules as $key => $value )
                                  @foreach ( $value->access_modules as $key_access => $value_access)
                                    <option value="{{ $value_access->id }}">{{ $value->modules_name }}</option>
                                  @endforeach
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group">
                              <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </form>

                        <ul>
                          @foreach ( $detail_user->user_modules as $key => $value )
                            <li>{{ $value->access_modules->modules->modules_name }}</li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /.tab-pane -->
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
</body>
</html>
