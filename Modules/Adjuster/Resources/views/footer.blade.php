
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
<script type="text/javascript">
	$(function () {
		$.ajaxSetup({
	      headers: {
	          'X-CSRF-Token': $('input[name=_token]').val()
	      }
	    });

		loadTodoList();

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
			}
		});
	}
</script>