			<script>
			deleteMarkers();
			var infoPop;
			var imageMarkers = new Array();
			var difficultyIcon = "";
			var activityIcons = "";
			var distance = "";	
			var rating = "";	
			var duration = "";				
		
			</script>
			
			@foreach ($activities as $activity)
				<script>
				imageMarkers[{{ $activity->id }}] = '{{ $activity->marker }}';
				</script>
			@endforeach
			@if ($trails->count())
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Trail Name</th>
						<th>Rating</th>	
						<th>Level</th>	
						<th>Length</th>					
					</tr>
				<thead>
				<tbody>
				@foreach ($trails as $trail)
					@foreach ($trail->activity as $trail_activity)
						<script>
							activityIcons += "<img src='{{  asset($trail_activity->mini) }}' >";
						</script>
						
					 @endforeach	
			
					<script>
							duration = '{{ $trail->duration }}';
					</script>	
					<tr>
					<td>{{ link_to("/trails/{$trail->id}", $trail->name) }}

					</td>
					<td>
					@if ($trail->rating_count > 0)
						<div class="ratings">
							@for ($i=1; $i <= 5 ; $i++)
								<span class="glyphicon glyphicon-star{{ ($i <= $trail->rating_cache) ? '' : '-empty'}}"></span>
							@endfor
							</p>
						</div>
					@else
						<div class="ratings">
							@for ($i=1; $i <= 5 ; $i++)
								<span class="glyphicon glyphicon-star{{ ($i <= $trail->rating) ? '' : '-empty'}}"></span>
							@endfor
							</p>
						</div>
					@endif
					</td>
					<td>
					 @foreach ($difficulties as $difficulty)
						@if ($trail->difficulty == $difficulty->name)
							<img src="{{ asset($difficulty->icon) }}">
							<script>
								difficultyIcon = '{{ asset($difficulty->mini) }}';
							</script>
						@endif
					 @endforeach
					</td>	
					<td>
						{{ number_format((float)$trail->distance, 2) }} km
						<br/>
						{{ $trail->duration }} hrs
						<script>
							distance = '{{ number_format((float)$trail->distance, 2) }}';
						</script>						
					</td>	
					<td>
						{{ link_to("kml/{$trail->kml_name}", "GPS") }}
					</td>	
					</tr>
					@if (count($trail->image) > 0)
						
						<script>
						infoPop = "<a href='http://trails.greenways.ca/trails/{{$trail->id}}'>{{$trail->name}}<br><img src='http://trails.greenways.ca/uploads/images/{{ $trail->image[0]->file }}' width='80'></a><br>  " + distance + " km <br>" + duration + " hrs  <img src='" + difficultyIcon + "'><br>" + rating + "<br>" + activityIcons;
						</script>
					@else
						<script>
						infoPop = "<a href='http://trails.greenways.ca/trails/{{$trail->id}}'>{{$trail->name}}</a><br>  " + distance + " km <br>" + duration + " hrs  <img src='" + difficultyIcon + "'><br>" + rating + "<br>" + activityIcons;
						</script>
					@endif
					<script>
					//addMarker("{{$trail->name}}", "{{$trail->start_lat}}", "{{$trail->start_lng}}", infoPop, imageMarkers[{{ $trail->activity[0]->id }}]);
					rating = "";
					activityIcons = "";
					</script>
				
					@if ($activity_image_id  > 0)
						<script>
						addMarker("{{$trail->name}}", "{{$trail->start_lat}}", "{{$trail->start_lng}}", infoPop, imageMarkers[{{ $activity_image_id }}]);
						</script>							
					@else
						<script>
						addMarker("{{$trail->name}}", "{{$trail->start_lat}}", "{{$trail->start_lng}}", infoPop, imageMarkers[{{ $trail->activity[0]->id }}]);
						</script>							
					@endif

				@endforeach
				<script>
						setBounds(markers);
				</script>
				</tbody>
			</table>
			</div>
			@else
				<p>There are no matching trails. Try selecting different criteria. </p>
			@endif	
			
	