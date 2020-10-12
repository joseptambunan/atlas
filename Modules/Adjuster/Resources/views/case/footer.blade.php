<!-- bootstrap datepicker -->

<!-- jQuery 3 -->
<script src="{{ url('/')}}/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('/')}}/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="{{ url('/')}}/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/')}}/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{ url('/')}}/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{ url('/')}}/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('/')}}/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('/')}}/assets/dist/js/demo.js"></script>
<!-- page script -->
<script src="{{url('/')}}/assets/plugins/customd-jquery-number-c19aa59/jquery.number.min.js"></script>
<script type="text/javascript">
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });

    $('#example3').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
  });

  $( document ).ready(function() {
  $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('input[name=_token]').val()
      }
    });

  $("#btn_expenses").click(function(){
    $("#btn_expenses").hide();
    $("#loading").show();
    saveExpenses('new');
  });

  $("#btn_expenses_revisi").click(function(){
    $("#btn_expenses_revisi").hide();
    $("#loading_revisi").show();
    saveExpenses('update');
  });

  $("#ammount_expenses").number(true);
  $("#ammount_revisi").number(true);
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

   function saveExpenses(method = "new"){
    var data = new FormData();
    //Form data
    var form_data = $('#upload_expenses').serializeArray();
    if ( method == "update"){
      var form_data = $('#upload_expenses_revisi').serializeArray();
    }

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

   function setRevisi(id){
    $("#expenses_id").val($("#reference_id_" + id).val());
    $("#type_revisi").val($("#reference_type_" + id).val());
    $("#ammount_revisi").val($("#reference_ammount_" + id).val());
    $("#desc_revisi").val($("#reference_desc_" + id).val());

    var request = $.ajax({
      url : "{{url('/')}}/adjuster/case/history_approval",
      dataType : "json",
      data : {
        id : id
      },
      type :"post"
    });

    request.done(function(data){
      $("#list_approval").html(data.html);
    })
  }

  function removeDataExpenses(id){
    if ( confirm("Are you sure to remove this data")){
      var request = $.ajax({
        url : "{{url('/')}}/adjuster/case/remove_expenses",
        data : {
          id : id
        },
        type : "post",
        dataType : "json"
      });

      request.done(function(data){
        if ( data.status == "0"){
          alert("Data has been deleted");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
  }
</script>