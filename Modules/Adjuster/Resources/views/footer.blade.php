
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
<!-- bootstrap datepicker -->
<script src="{{url('/')}}/assets/plugins/customd-jquery-number-c19aa59/jquery.number.min.js"></script>

<script type="text/javascript">
	$(function () {
		$.ajaxSetup({
	      headers: {
	          'X-CSRF-Token': $('input[name=_token]').val()
	      }
	    });

		loadTodoList();
		$("#btn_expenses").click(function(){
			$("#btn_expenses").hide();
			$("#loading").show();
			saveExpenses();
		});

	});

	function loadTodoList(){
		var request = $.ajax({
			url : "{{ url('/')}}/adjuster/todolist",
			dataType : "json",
			data : {
				adjuster_id : $("#adjuster_id").val()
			},
			type : "post"
		});

		request.done(function(data){
			$("#box_todo_list").html("");

			if ( data.status == 0 ){
				$("#box_todo_list").html(data.html);
				$("#iou_number").html(data.html_iou);
			}
		});
	}

	function saveExpenses(){
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

	function loadIouCases(){
		var request = $.ajax({
			url : "{{url('/')}}/adjuster/loadcases",
			dataType : "json",
			data :{
				id : $("#iou_number").val()
			},
			type : "post"
		});

		request.done(function(data){
			if ( data.status == 0 ){
				$("#iou_list_id").html(data.html);
			}else{
				alert("Case Not Found");
				window.location.reload();
			}

		})
	}
</script>