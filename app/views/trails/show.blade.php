@extends('layouts.trail')

@section('scripts')
  {{HTML::script('js/expanding.js')}}
  {{HTML::script('js/starrr.js')}}
  {{HTML::script('js/jquery.PrintArea.js')}}

  <script type="text/javascript">
    $(function(){
	
	  $("#printqr").click(function(){
		$("div#PrintAreaQR").printArea( );
	  });
	  
	  $("#printtrail").click(function(){
	  alert("Printing");
		$("div#PrintAreaTrail").printArea( );
	  });

      // initialize the autosize plugin on the review text area
      $('#new-review').autosize({append: "\n"});

      var reviewBox = $('#post-review-box');
      var newReview = $('#new-review');
      var openReviewBtn = $('#open-review-box');
      var closeReviewBtn = $('#close-review-box');
      var ratingsField = $('#ratings-hidden');

      openReviewBtn.click(function(e)
      {
        reviewBox.slideDown(400, function()
          {
            $('#new-review').trigger('autosize.resize');
            newReview.focus();
          });
        openReviewBtn.fadeOut(100);
        closeReviewBtn.show();
      });

      closeReviewBtn.click(function(e)
      {
        e.preventDefault();
        reviewBox.slideUp(300, function()
          {
            newReview.focus();
            openReviewBtn.fadeIn(200);
          });
        closeReviewBtn.hide();
        
      });

      // If there were validation errors we need to open the comment form programmatically 
      @if($errors->first('comment') || $errors->first('rating'))
        openReviewBtn.click();
      @endif

      // Bind the change event for the star rating - store the rating value in a hidden field
      $('.starrr').on('starrr:change', function(e, value){
        ratingsField.val(value);
      });
    });
  </script>
@stop

@section('content')
	<div id="PrintAreaTrail">
		<h2>{{ $trail->name }}</h2>
		<br/>
		@if ($trail->rating_count > 0)
			<div class="ratings">
                <p class="pull-right"><a href="#reviews-anchor">{{$trail->rating_count}} {{ Str::plural('review', $trail->rating_count);}}/{{ Str::plural('comment', $trail->rating_count);}}</a></p>
                <p>
                @for ($i=1; $i <= 5 ; $i++)
                    <span class="glyphicon glyphicon-star{{ ($i <= $trail->rating_cache) ? '' : '-empty'}}"></span>
                @endfor
                {{ number_format($trail->rating_cache, 1);}} stars
                </p>
            </div>
		@else
			@for ($i=1; $i<=$trail->rating; $i++)
					<span class="glyphicon glyphicon-star"></span>
			@endfor	
		@endif
		<br/>
		@if ($trail->organization)
			{{ $trail->organization->organization }}
		@endif
		<br/>
		{{ $trail->location }}
		<br/>
		
		@if (count($trail->activity) > 0)
			<ul class="list-inline">
			@foreach ($trail->activity as $activity)
				<li>
				<label data-toggle="tooltip" data-placement="bottom" title="{{ $activity->name }}">
				<img src="{{ asset($activity->icon) }}" class="img-responsive inline-block" alt="{{ $activity->name }}"/>
				</label>
				</li>			
			@endforeach
			</ul>
		@endif

		<ul class="list-inline"> 
		<li>
		@if (is_numeric($trail->distance))
			{{ number_format($trail->distance, 2) }} km<br>
		@else
			{{ number_format(((float)$trail->distance), 2) }} km<br>
		@endif
		Distance
		<br><img src="{{ asset('images/trail/distance.png') }}" height="55" >
		</li>
		<li>
		<div id="elevation_gain">{{ $trail->elevation_gain }}</div> 
		Elevation Gain
		<br><img src="{{ asset('images/trail/elevation_gain.png') }}" height="55" >
		</li>	
		<li>
		<div id="trail_head_elevation">{{ $trail->trail_head_elevation }}</div>
		Trail Head Elevation
		<br><img src="{{ asset('images/trail/trail_head_elevation.png') }}" height="55" >
		</li>	
		<li>
		<div id="max_elevation">{{ $trail->max_elevation }}</div>
		Maximum Elevation
		<br><img src="{{ asset('images/trail/max_elevation.png') }}" height="55" >
		</li>	
		<li>
		<div id="cumulative_elevation">{{ $trail->cumulative_elevation }}</div>
		Cumulative Elevation
		<br><img src="{{ asset('images/trail/cumulative_elevation.png') }}" height="55">
		</li>
		<li>
		{{ $trail->duration }} hour(s)<br>
		Average Time
		<br><img src="{{ asset('images/trail/duration.png') }}" height="55" >
		</li>
		</ul>
		
		@if (count($trail->image) > 0)
		<br/><br/>
			<div id="owl-demo">
			@foreach ($trail->image as $image)
				<div class="item"><img src="{{ asset('/uploads/images/' . $image->file) }}" alt="{{ $image->name }}"></div>
				@endforeach
			</div>
		@endif
				
		
		@if ($trail->description)
		<br>
		<br><img src="{{ asset('images/trail/description.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Trail Description</h4>
		<p>
			{{ $trail->description }}
		</p>
		@endif
		
		@if ($trail->directions)
		<br><img src="{{ asset('images/trail/directions.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Directions to Trail Head</h4>
		<p>
			{{ $trail->directions }}
		</p>
		@endif
		
		<br><img src="{{ asset('images/trail/detailed_map.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Detailed Map</h4>

		
		
		    <script>
	
var elevator;
var map;
var path;
var chart;
var ctaLayer;

var mousemarker = null;
var mm_infowindow_open = false;
var infowindow = null;
var elevations = null;

// Load the Visualization API and the columnchart package.
google.load('visualization', '1', {packages: ['columnchart']});
google.setOnLoadCallback(initialize);
			
function initialize() {
  var invermere = new google.maps.LatLng({{ $trail->start_lat }}, {{ $trail->start_lng }});
  var mapOptions = {
    zoom: 11,
    center: invermere,
	scrollwheel: false,
	mapTypeId: google.maps.MapTypeId.TERRAIN
  }

  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  
  // Create a new chart in the elevation_chart DIV.
  chart = new google.visualization.ColumnChart(document.getElementById('elevation_chart'));
  
  ctaLayer = new google.maps.KmlLayer({
    url: 'http://trails.greenways.ca/kml/{{ $trail->kml_name }}'
  });
  ctaLayer.setMap(map);
  
	var weatherLayer = new google.maps.weather.WeatherLayer({
        temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS
    });
    weatherLayer.setMap(map);

    var cloudLayer = new google.maps.weather.CloudLayer();
    cloudLayer.setMap(map);  
	
	// Create an ElevationService.
	elevator = new google.maps.ElevationService();
	
	infowindow = new google.maps.InfoWindow({});
    google.visualization.events.addListener(chart, 'onmouseover', function(e) {
      if (mousemarker == null) {
        mousemarker = new google.maps.Marker({
          position: elevations[e.row].location,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
        });
        var contentStr = "elevation="+elevations[e.row].elevation+"<br>location="+elevations[e.row].location.toUrlValue(6);
		mousemarker.contentStr = contentStr;
		google.maps.event.addListener(mousemarker, 'click', function(evt) {
		mm_infowindow_open = true;
        infowindow.setContent(this.contentStr);
		infowindow.open(map,mousemarker);
        });
      } else {
        var contentStr = "elevation="+elevations[e.row].elevation+"<br>location="+elevations[e.row].location.toUrlValue(6);
		mousemarker.contentStr = contentStr;
        infowindow.setContent(contentStr);
        mousemarker.setPosition(elevations[e.row].location);
        // if (mm_infowindow_open) infowindow.open(map,mousemarker);
      }	
	
	});
  
	getGeoPath('http://trails.greenways.ca/kml/{{ $trail->kml_name }}');
}

    function getGeoPath(uri){
		jQuery.ajax({
			type: "GET",
			url: uri,
			dataType: "text",
			success: function(data) { 
                //alert("success");
				var xml = jQuery(data);
				var doc = xml.children("Document");
				var kPath = [];

				if (doc.length > 0){
					var coordinates = jQuery(doc).find("LineString").children("coordinates");
					coordinates.each(function(){
						var coords = jQuery(this).text();
						if (coords.length > 0){
							var points = [];
							coord = coords.replace(/\s/g,' ');
							var coordStrings = coord.split( ' ' );
							for ( i = 0; i < coordStrings.length; i++) {
								vals =  coordStrings[i].replace(/[^0-9,.-]/g,'');
								vals = vals.split(',')
								if(!isNaN(vals[0]) && !isNaN(vals[1])){
									loc = new google.maps.LatLng(vals[1], vals[0]);
									points.push(loc); 
								}
							}
							kPath = kPath.concat(points);
						}
					});
					var coords = doc.find('gx\\:Track').find('gx\\:coord');
					if (coords.length > 0){
						var points = [];
						coords.each(function(){
							var coords = jQuery(this).text();
								coord = coords.replace(/\s/g,' ');
								var coordStrings = coord.split( ' ' );
									if(!isNaN(coordStrings[0]) && !isNaN(coordStrings[1])){
										loc = new google.maps.LatLng(coordStrings[1], coordStrings[0]);
										points.push(loc); 
									}
						});
						kPath = kPath.concat(points);
					}
					
				}
				if(kPath.length > 1){					
					if(kPath.length > 100){
						while(kPath.length > 200){
							var i = kPath.length-1;
							for (kPath.length-1; i > 0; i = i-2) 
							{
								kPath.splice(i,1);
							}
						}
					}
					drawPath(kPath);
					//alert(kPath);
				}
			},
			complete:function (jqXHR, textStatus){
				/* enable for error check in loading gpx*/
				if(textStatus == "error"){
					alert('Error: ' + jqXHR.responseText);
				}
			}    
		});	
	}	
	
	function drawPath(path) {

	  // Create a PathElevationRequest object using this array.
	  // Ask for 256 samples along that path.
	  var pathRequest = {
		'path': path,
		'samples': 256
	  }

	  // Initiate the path request.
	  elevator.getElevationAlongPath(pathRequest, plotElevation);
	}
	
// Takes an array of ElevationResult objects, draws the path on the map
// and plots the elevation profile on a Visualization API ColumnChart.
function plotElevation(results, status) {
  if (status != google.maps.ElevationStatus.OK) {
    return;
  }
  elevations = results;

  // Extract the elevation samples from the returned results
  // and store them in an array of LatLngs.
  var maxElevation = 0;
  var minElevation = 10000; 
  var altDiff = 0;
  var cumElevation = 0;
  var elevationGain = 0;
  var trailheadElevation = elevations[0].elevation;
  
  var elevationPath = [];
  for (var i = 0; i < results.length; i++) {
    elevationPath.push(elevations[i].location);
	
	if (elevations[i].elevation > maxElevation){
		maxElevation = elevations[i].elevation;
	}
	if (elevations[i].elevation < minElevation){
		minElevation = elevations[i].elevation;
	}	
	if (i > 1){
		altDiff = elevations[i].elevation - elevations[i-1].elevation;
		cumElevation += Math.max(0, altDiff);
	}
  }
  elevationGain = maxElevation - minElevation;
  
  if(!$.trim( $('#max_elevation').html()).length ) {
	$('#max_elevation').html(maxElevation.toFixed(2) + " m");
  }
  if(!$.trim( $('#elevation_gain').html()).length ) {  
	$('#elevation_gain').html(elevationGain.toFixed(2) + " m");
  }
  if( !$.trim($('#trail_head_elevation').html()).length ) { 
	$('#trail_head_elevation').html(trailheadElevation.toFixed(2) + " m"); 
  }
  if( !$.trim($('#cumulative_elevation').html()).length ) { 
	$('#cumulative_elevation').html(cumElevation.toFixed(2) + " m");  
  }
  
  //document.getElementById("max_elevation").innerHTML=maxElevation.toFixed(2) + " m"; 
  //document.getElementById("elevation_gain").innerHTML=elevationGain.toFixed(2) + " m"; 
  //document.getElementById("trail_head_elevation").innerHTML=trailheadElevation.toFixed(2) +" m"; 
  //document.getElementById("cumulative_elevation").innerHTML=cumElevation.toFixed(2) + " m"; 
  
  // Display a polyline of the elevation path.
  var pathOptions = {
    path: elevationPath,
    strokeColor: '#0000CC',
    opacity: 0.4,
    map: map
  }
  polyline = new google.maps.Polyline(pathOptions);

  // Extract the data from which to populate the chart.
  // Because the samples are equidistant, the 'Sample'
  // column here does double duty as distance along the
  // X axis.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Sample');
  data.addColumn('number', 'Elevation');
  for (var i = 0; i < results.length; i++) {
    data.addRow(['', elevations[i].elevation]);
  }

  // Draw the chart using the data within its DIV.
  document.getElementById('elevation_chart').style.display = 'block';
  chart.draw(data, {
    height: 150,
    legend: 'none',
    titleY: 'Elevation (m)'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	
		<div id="map-canvas"></div>

		<br><img src="{{ asset('images/trail/elevation.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Elevation Profile</h4>
		<div id="elevation_chart"></div>	

		@if ($trail->maintenance)
			<br><img src="{{ asset('images/trail/maintenance.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Planned Mainenance and Trail Events</h4>
			<p>{{ $trail->maintenance }}</p>
		@endif
		
		@if ($trail->surface)
			<br><img src="{{ asset('images/trail/trail_surface.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Trail Surface</h4>
			<p>{{ $trail->surface }}</p>
		@endif
		
		@if ($trail->season)
			<br><img src="{{ asset('images/trail/trail_season.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Trail Season</h4>
			<p>{{ $trail->season }}</p>
		@endif
		
		@if ($trail->surface)
			<br><img src="{{ asset('images/trail/points.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Points of Interest</h4>
			@if (count($trail->image) > 0)
				<br/><br/>

				@foreach ($trail->point as $point)
					{{ $point->name }} {{ $point->lat }} {{ $point->lng }}
					<br/>
					<div class="item"><img src="{{ asset('/uploads/points/' . $point->file) }}" alt="{{ $point->name }}" width="200" ></div>
				@endforeach

			@endif
		@endif
		
		@if ($trail->open)
			<br><img src="{{ asset('images/trail/approved.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Sanctioned Status</h4>
			<p>{{ $trail->open }}</p>
		@endif
		
		@if ($trail->hazards)
			<br><img src="{{ asset('images/trail/hazards.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Uncommon Hazards</h4>
			<p>{{ $trail->hazards }}</p>
		@endif
		
		@if ($trail->land_access)
			<br><img src="{{ asset('images/trail/land_access.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Land Access Issues</h4>
			<p>{{ $trail->land_access }}</p>
		@endif
		
		@if (count($trail->amenity) > 0)
		<br/>
		<h4>Trail Features</h4>
			<ul class="list-inline">
			@foreach ($trail->amenity as $amenity)
				<li>
				<label data-toggle="tooltip" data-placement="bottom" title="{{ $amenity->name }}">
				<img src="{{ asset($amenity->icon) }}" class="img-responsive inline-block" alt="{{ $amenity->name }}"/>
				</label>
				</li>			
			@endforeach
			</ul>
		@endif
		
		@if (Auth::check())
			@if ((Auth::user()->hasRole('administrator')) or (Auth::user()->id == $trail->user_id))
				{{ link_to_route('trails.edit', 'Edit', array($trail->id), array('class' => 'btn btn-info')) }}
				<br/><br/>
			@endif
		
		@endif
		
	</div>
	<a href="#"><span id="printtrail">Print Trail</span></a>
	<br/><br/>
	
		<div id="qrprint">
		<img src="https://api.qrserver.c
		om/v1/create-qr-code/?data={{ rawurlencode("http://trails.greenways.ca/trails/" . $trail->id)}}&size=100x100" alt="" title="" width="100" />
		</div>
		<a href="#" role="button" class="btn" data-toggle="modal" data-target="#qrmodal">Enlarge QR to print</a>

	
	<br/><br/>

            <div class="well" id="reviews-anchor">
              <div class="row">
                <div class="col-md-12">
                  @if(Session::get('errors'))
                    <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                       <h5>There were errors while submitting this review:</h5>
                       @foreach($errors->all('<li>:message</li>') as $message)
                          {{$message}}
                       @endforeach
                    </div>
                  @endif
                  @if(Session::has('review_posted'))
                    <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5>Your review has been posted!</h5>
                    </div>
                  @endif
                  @if(Session::has('review_removed'))
                    <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5>Your review has been removed!</h5>
                    </div>
                  @endif
                </div>
              </div>
			  @if (Auth::check())
              <div class="text-right">
                <a href="#reviews-anchor" id="open-review-box" class="btn btn-success btn-green">Leave a Review/Comment</a>
              </div>
              <div class="row" id="post-review-box" style="display:none;">
                <div class="col-md-12">
				  {{ Form::open(['id' => 'uploadreview', 'route' => 'trails.reviewupload', 'files' => true, 'remote-success-message' => 'Review has been added']) }}
				  {{Form::hidden('trailid', $trail->id)}}
                  {{Form::hidden('rating', null, array('id'=>'ratings-hidden'))}}
                  {{Form::textarea('comment', null, array('rows'=>'5','id'=>'new-review','class'=>'form-control animated','placeholder'=>'Enter your review here...'))}}
                  <div class="text-right">
                    <div class="stars starrr" data-rating="{{Input::old('rating',0)}}"></div>
                    <a href="#" class="btn btn-danger btn-sm" id="close-review-box" style="display:none; margin-right:10px;"> <span class="glyphicon glyphicon-remove"></span>Cancel</a>
                    <button class="btn btn-success btn-lg" type="submit">Save</button>
                  </div>
                {{Form::close()}}
                </div>
              </div>
			  @else
			  <div class="row" id="post-review-box" >
                <div class="col-md-12">
					You must be logged in to leave a review/comment.
				</div>
			  </div>
			  
			  @endif

              @foreach($reviews as $review)
              <hr>
                <div class="row">
                  <div class="col-md-12">
                    @for ($i=1; $i <= 5 ; $i++)
                      <span class="glyphicon glyphicon-star{{ ($i <= $review->rating) ? '' : '-empty'}}"></span>
                    @endfor
					
                    {{ $review->user ? $review->user->username : 'Anonymous'}} <span class="pull-right">{{$review->timeago}}</span> 
                    
                    <p>{{{$review->comment}}}</p>
                  </div>
                </div>
              @endforeach
              {{ $reviews->links(); }}
            </div>
			
<!-- QR modal -->
<div class="modal fade" id="qrmodal" tabindex="-1" role="dialog" aria-labelledby="myQRmodal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myImagemodal">{{ $trail->name }}</h4>
			</div>
			<div class="modal-body">
				<div id="PrintAreaQR">
	<img src="https://api.qrserver.c
			om/v1/create-qr-code/?data={{ rawurlencode("http://trails.greenways.ca/trails/" . $trail->id)}}&size=300x300" alt="" title=""  />
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button id="printqr" class="btn btn-primary">Print</button>
			</div>
		</div>
	</div>
</div>
<!-- End QR modal -->
    
@stop