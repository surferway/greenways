$(document).ready(function() {
	$('#reset').on('click', function(){
		alert('Reset');
		$("#searchForm").trigger("reset");
		$('#distance_search').slider.refresh;
		$('#duration_search').slider.refresh
		$('#searchForm').find('input:text, input:password, select, textarea').val('');
		$('.filed').prop('checked', false);
		loadForm();
		alert('Done');
	});	
	
	
});	