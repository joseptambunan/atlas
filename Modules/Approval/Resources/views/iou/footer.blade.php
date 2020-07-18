
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
    if ( confirm("Are you sure to approve this IOU")){
      var request = $.ajax({
        url : "{{ url('/')}}/approval/submit/",
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
          alert("IOU Has been Approved by You");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
  }	

    
</script>