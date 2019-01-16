@extends('layouts.app')

@section('script')
<script>
	var ajaxCall=function(){
		console.log("entro");
		$.ajax({
            type: 'POST',
            url:'/isacepted',
            data: {
            '_token':"{{ csrf_token() }}",
        	},
            success: function(data) {
            	$('#Menu_Noti').html(data.html);
            	if(data.size > 0){
            		$('#count_Notificaction').text(data.size.toString());
            	}
            	else
            	{
            		$('#count_Notificaction').text("");
            	}
            },
		    error: function (result) {
		        
		    }
        });
     }
    interval = setInterval(ajaxCall,10000);

	function stopfunction(){
		clearInterval(interval);
	};

</script>

<script>
	function viewNotification(id){
		console.log(id);
		$.ajax({
            type: 'POST',
            url: "/updateNotificaction",
            data: {
                '_token':"{{ csrf_token() }}",
                'notification_id':id
            },
            success: function(data) {
            	console.log(data);

        		    $('#modalinfo .modal-body #parrafo').text(data.texto);
        			$('#modalinfo').modal('show');
            },
		    error: function (result) {
		        $('#modalinfo .modal-body #parrafo').text("Existieron algunos errores.");
        		$('#modalinfo').modal('show');
		    }
        });
	};
</script>
@endsection
