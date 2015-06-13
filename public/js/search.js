$(document).ready(function() {

	$('#activity_search input[type=checkbox]').click(function(){
		loadForm();
		//alert(JSON.stringify(searchData.serializeArray()));
		//alert(activityIDs + " " + difficultyIDs);
	});
	
	$('#difficulty_search input[type=checkbox]').click(function(){
		loadForm();
	});	
	
	$('#distance_search').on('slideStop', function(){
		loadForm();
	});
	
	$('#duration_search').on('slideStop', function(){
		loadForm();
	});	
	
	
	//setTimeout(loadTrailTable,500);
	
	// success:function(articles) {
		// $(".articles").html(articles);
	// }
	
	// $('#uploadimage').ajaxForm({
		//target: '#image_output', 
		// success: function(response) { 
			//alert(response); 
			// $('#uploadimage').resetForm();
			// $('#imagemodal').modal('hide');
			// $("#images").append(response);
		// }
		
	// }); 
	
	function loadForm(){
		var activityIDs = $("#activity_search input:checkbox:checked").map(function(){
			return $(this).val();
		}).get();
		var difficultyIDs = $("#difficulty_search input:checkbox:checked").map(function(){
			return $(this).val();
		}).get();
		var duration = $("#duration-slider").val();
		var distance = $("#distance-slider").val();
		
		var searchData = new FormData();
		searchData.append("username", "surferway");
		searchData.append("activities", activityIDs);
		searchData.append("difficulties", difficultyIDs);	
		searchData.append("duration", duration);	
		searchData.append("distance", distance);			
		
		loadFormData(searchData);
	}
	
	function loadFormData(formData){
		 $.ajax({ 
			url: 'http://trails.greenways.ca/trails_table',
			type:"post",
			processData: false,
			contentType: false,
			cache: false,
			dateType: 'json',
			data: formData,
			success:function(response){
				$("#trails_table").html(response);
			}
		});
	}
	
	function loadTrailTable(){
		 $.ajax({ 
			url: 'http://trails.greenways.ca/trails_table',
			type:"get",
			success:function(response){
				$("#trails_table").html(response);
			}
		});
	}
	
	setTimeout(loadTrailTable,500);
	
	$("#duration-slider").slider({});
	  
	$("#distance-slider").slider({});
	
	$('#reset').on('click', function(){
		$('input[type="checkbox"]').prop( "checked", false );
        $("#duration-slider").slider('setValue', [0,8]);
        $("#distance-slider").slider('setValue', [0,100]);		
		loadTrailTable();
	});	

});