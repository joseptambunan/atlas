<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @include("master::case.header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include ("master::case.navbar")
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
              <h3 class="box-title">Master Adjuster</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6">
                <table class="table table-bordered table-hover">
                  <thead class="header_background">
                    <tr>
                      <td>No.</td>
                      <td>Name</td>
                      <td>Delete</td>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i=0; @endphp
                    @foreach( $case_number->adjusters as $key => $value )
                      @if ( $value->deleted_at == "")
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $value->adjuster->name }}</td>
                        <td><button class="btn btn-danger" onClick="removeAdjuster('{{ $value->id}}')">Delete</button></td>
                      </tr>
                      @php $i++; @endphp
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              
              <div class="col-md-12">
                <hr/>
                <h3><center>Adjuster List</center></h3>
                <form action="{{ url('/')}}/casenumbers/saveadjusters" method="post" name="form1">
                {{ csrf_field() }}
                <input type="hidden" name="casenumber_id" value="{{$case_number->id}}">
                <button class="btn btn-info" type="submit">Simpan</button>
                <a class="btn btn-warning" href="{{ url('/')}}/casenumbers/show/{{$case_number->id}}">Back</a>
                <table id="example4" class="table table-bordered table-hover">
                  <thead class="header_background">
                  <tr>
                    <th>No.</th>
                    <th>Adjuster Name</th>
                    <th>Email</th>
                    <th>Checklist</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php $i=0; @endphp
                    @foreach ( $master_adjuster as $key => $value )
                      @if ( $value->deleted_at == "")
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $value->name}}</td>
                        <td>{{ $value->email}}</td>
                        <td><input type="checkbox" name="adjuster[]" value="{{ $value->id}}" /></td>
                      </tr>
                      @php $i++;@endphp
                      @endif
                    @endforeach
                  </tbody>
                </table>
                </form>  
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

   function removeAdjuster(id){
      if ( confirm("Apakah anda yakin ingin menghapus data ini")){
        var request = $.ajax({
          url : "{{ url('/')}}/casenumbers/remove/adjuster",
          dataType : "json",
          data :{
            id : id
          },
          type : "post"
        });

        request.done(function(data){
          if ( data.status == "0"){
            alert("Data has been deleted");
          }
        });

        window.location.reload();
      }else{
        return false;
      }

   }
</script>
</body>
</html>
