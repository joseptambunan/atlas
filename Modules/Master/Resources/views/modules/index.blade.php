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
  {{ csrf_field() }}
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
        <div class="col-xs-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Master Document</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-12">
                <form role="form" method="post" name="form1" action="{{ url('/')}}/master/modules/create">
                  <input type="hidden" name="module_id" id="module_id">
                  {{ csrf_field() }}
                  <!-- text input -->
                  <div class="form-group">
                    <label>Add Document</label>
                    <input type="text" class="form-control" autocomplete="off" name="module" id="module" onkeyup="validatedata();" required>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-success" type="submit">Add</button>
                  </div>
                </form>
              </div>
            
              <table id="example2" class="table table-bordered table-hover">
                <thead class="header_background">
                <tr>
                  <th>No.</th>
                  <th>Nama Modules</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ( $master_modules as $key => $value )
                    @if ( $value->deleted_at == "")
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>
                        {{ $value->modules_name}}
                      </td>
                      <td>
                        <button class="btn btn-info" onClick="editmodules('{{ $value->id}}','{{ $value->modules_name }}')">Edit</button>
                        <button class="btn btn-danger" onClick="removemodules('{{ $value->id}};')">Delete</button>
                      </td>
                    </tr>
                    @endif
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


  function removemodules(id){
    if ( confirm("Are you sure to delete this module")){
      var request = $.ajax({
        url : "{{url('/')}}/master/modules/delete",
        dataType: "json",
        data : {
          id: id
        },
        type : "post",
      });

      request.done(function(data){
        if ( data.status == 0 ){
          alert("Data has been deleted");
        }
        window.location.reload();
        return false;
      });

    }else{
      return false;
    }
  }

  function editmodules(id,name){
    $("#module_id").val(id);
    $("#module").val(name);
  }

  function validatedata(){
    if ( $("#module").val() == "" ){
      $("#module_id").val() == "";
    }
  }
</script>
</body>
</html>
