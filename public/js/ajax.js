
(function () {

	$('form[data-remote]').on('submit', function(e){
		var form = $(this);
		var method = form.find('input[name="_method"]').val() || 'POST';
		var url = form.prop('action');
	
		$.ajax({
			type: method,
			url: url,
			data: form,
			dataType: "text",
			//data: form.serialize(),
			//dataType: "json", 
			success: function(response){
			
				$('#imagemodal').modal('hide');
			
				var message = form.data('remote-success-message');
				
				alert(JSON.stringify(response));
				
				//message && alert(message);
				if (message){
					$('.flash').html(message).fadeIn(300).delay(2500).fadeOut(300);
				}
				
				
				
			}
			
		
		});
	
		e.preventDefault();
	
	});
	
	$('input[data-confirm], button[data-confirm]').on('click', function(e){
		var input = $(this);
		var form = input.closest('form');
		
		input.prop('disabled', 'disabled');
		
		if ( ! confirm(input.data('confirm'))){
		
			e.preventDefault();
		
		}
		
		input.removeAttr('disabled');
	
	});
	
	$('#uploadimage').ajaxForm({
		beforeSubmit: function(){ $('#image-loading').show(); },
		//target: '#image_output', 
		success: function(response) { 
			//alert(response); 
			$('#uploadimage').resetForm();
			$('#imagemodal').modal('hide');
			$("#images").append(response);
		}
		
	}); 
	
	$('#uploadpoint').ajaxForm({
		beforeSubmit: function(){ $('#point-loading').show(); },
		//target: '#point_output', 
		success: function(response) { 
			//alert(response); 
			$('#uploadpoint').resetForm();
			$('#pointmodal').modal('hide');
			$("#points").append(response);
		}
		
	}); 
	
	$("body").on("click", ".delete", function (e) {
		$(this).parent("div").remove();
	});

})();