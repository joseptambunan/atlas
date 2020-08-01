
<!-- jQuery 3 -->
<script src="{{url('/')}}/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('/')}}/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="{{url('/')}}/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{url('/')}}/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{url('/')}}/assets/dist/js/demo.js"></script>
<!-- Select2 -->
<script src="{{url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="{{url('/')}}/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap datepicker -->
<script src="{{url('/')}}/assets/plugins/customd-jquery-number-c19aa59/jquery.number.min.js"></script>
<script type="text/javascript">
	$(function () {
		$('.select2').select2();
		$('#datepicker_start').datepicker({
	      autoclose: true
	    });

		$('#datepicker_end').datepicker({
	      autoclose: true
	    });

    $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
      });

   });

  function setApprove(id){
    if ( confirm("Are you sure to approve this document")){
      var request = $.ajax({
        url : "{{ url('/')}}/approval/submit",
        dataType : "json",
        data :{
          approval_id : id,
          status : 3,
          description : "Approved"
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == "0"){
          alert("This Document Has been Approved by You");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
  }	

  function viewDetail(id){
    $("#expenses_name").val($("#ref_case_"+id).val());
    $("#expenses_type").val($("#ref_type_"+id).val());
    $("#expenses_ammount").val($("#ref_ammount_"+id).val());
    $("#expenses_description").val($("#ref_desc_"+id).val());
    $("#expenses_receipt").attr("href","{{ url('/')}}/approval/download/" + id);
    $("#expenses_approval_id").val($("#ref_approval_"+id).val());
  }

  function approveReject(status){
    if ( status == 2 ){
      if ( $("#reason").val() == "" ){
        alert("Please give the reason before reject");
        return false;
      }
    }

    var request = $.ajax({
      url : "{{ url('/')}}/approval/submit",
      dataType : "json",
      data : {
        approval_id : $("#expenses_approval_id").val(),
        status : status,
        description : $("#reason").val()
      },
      type : "post"
    });

    request.done(function(data){
      if ( data.status == 0 ){
        alert("Data has been updated");
      }

      window.location.reload();
    })
  }
</script>