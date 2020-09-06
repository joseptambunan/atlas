
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
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="{{url('/')}}/assets/plugins/customd-jquery-number-c19aa59/jquery.number.min.js"></script>
<script>
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

    $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('input[name=_token]').val()
      }
    });

    $('.select2').select2();
    $("#ammount_expenses").number(true);
  })


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

   function submitPengembalian(){
    if ( confirm("Are you sure to save data ")){
      if ( $("#pengembalian").val() == "" ){
        alert("Please insert struck number ");
        return false;
      }else{
        var request = $.ajax({
          url : "{{url('/')}}/casenumbers/update_return",
          dataType : "json",
          data : {
            id : $("#casenumber_id").val(),
            pengembalian : $("#pengembalian").val()
          },
          type : "post"
        });

        request.done(function(data){
          if ( data.status == 0 ){
            alert("Data has updated");
          }

          window.location.reload();
        });
      }
    }else{
      return false;
    }
   }

   function setCalculate(){
    var expenses = $("#ammount_expenses").val();
    expenses.replace(",","");
    calculate = ( parseInt(expenses) / parseInt($('.calculate:checked').length)) ;
    if ( calculate != "NaN"){
      $(".calculate_subtotal").val(calculate);
      $(".calculate_subtotal").number(true);
    }
   }

   function setRevisi(id){
    $("#expenses_id").val($("#reference_id_" + id).val());
    $("#type_revisi").val($("#reference_type_" + id).val());
    $("#ammount_revisi").val($("#reference_ammount_" + id).val());
    $("#desc_revisi").val($("#reference_desc_" + id).val());
  }
</script>