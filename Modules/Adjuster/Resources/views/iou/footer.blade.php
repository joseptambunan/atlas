
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

    $("#ammount").number(true);
    $("#ammount_expenses").number(true);
    $("#ammount_revisi").number(true);
    
    $("#btn_expenses").click(function(){
      $("#btn_expenses").hide();
      $("#loading").show();
      saveExpenses();
    });

    $("#btn_revisi_expenses").click(function(){
      $("#btn_revisi_expenses").hide();
      $("#loading_revisi").show();
      revExpenses();
    });

  });

	

  function removeData(id){
    if ( confirm("Are you sure to delete this data ")){
      var request = $.ajax({
        url : "{{ url('/')}}/adjuster/iou/delete",
        data : {
          id : id
        },
        dataType:"json",
        type : "post"
      });

      request.done(function(data){
        if ( data.status == 0 ){
          alert("Data has been deleted");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
  }

  function requestApproval(id, approval_id){
    if ( confirm("Are you sure to request approve ? ")){
      var request = $.ajax({
        url : "{{ url('/')}}/adjuster/iou/request_approval",
        dataType : "json",
        data : {
          document_id : id,
          approval_id : approval_id,
          document_type : 1
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == 0 ){
          alert("IOU has been send");
        }
        window.location.reload();
      });
    }else{
      return false;
    }
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

  function saveExpenses(){
    if ( $("#iou_list_id").val() == null || $("#iou_list_id").val() == "" || $("#type_expenses").val() == "" || $("#ammount_expenses").val() == "" ) {
      alert("Pleace complete data");
      $("#btn_expenses").show();
      $("#loading").hide();
      return false;
    }

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

  function setRevisi(id){
    $("#expenses_id").val($("#reference_id_" + id).val());
    $("#type_revisi").val($("#reference_type_" + id).val());
    $("#ammount_revisi").val($("#reference_ammount_" + id).val());
    $("#desc_revisi").val($("#reference_desc_" + id).val());
  }

  function revExpenses(){
    var data = new FormData();
    //Form data
    var form_data = $('#revisi_expenses').serializeArray();
    $.each(form_data, function (key, input) {
        data.append(input.name, input.value);
    });

    //File data
    var file_data = $('input[name="receipt"]')[0].files;

    for (var i = 0; i < file_data.length; i++) {
        data.append("receipt", file_data[i]);
    }


    var request = $.ajax({
      url : "{{url('/')}}/adjuster/case/revisi_expenses",
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

  $("#case_id").change(function(){
    var division = ($(this).children('option:selected').attr('data-attribute-division'));
    var insurance = ($(this).children('option:selected').attr('data-attribute-insurance'));
    $("#division_id").val(division);
    $("#insurance_id").val(insurance);
  });
   
</script>