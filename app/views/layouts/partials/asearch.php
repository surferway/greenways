	<h2>Trails Search</h2>
	
	<div class="row">
		<div class="col-md-5">
		
		<div class="form-group" id="activities">
		<input type="text" placeholder="Trail Name" ng-model="search.name">
		<br>
		<label>Search by Name </label>
		</div>
		
		<div class="form-group" id="activities">
		<input type="text" placeholder="Trail Location" ng-model="search.location">
		<br>
		<label>Search by Location </label>
		</div>
		
		<span ng-if="activities.length >  0">
			<div class="form-group" id="activities">
				<span ng-repeat="activity in activities">
					
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ activity.name }}">
					<input class="field" id="{{ activity.name }}" name="activities[]" type="checkbox" value="{{ activity.id }}" ng-model="useActivity[$index]">
					<label for="{{ activity.name }}"><img ng-src="{{ activity.icon }}"/></label>
					</label>
					
				</span>
				<br/>
				<label for="activities">Select Activity Type </label>
			</div>
		</span>
		 <pre ng-bind="useActivity"></pre>
						
		</div>
		<div class="col-md-7">
			<span ng-repeat="marker in markerList">
			
				{{ marker.id }} {{ marker.latitude }}  {{ marker.longitude }} <br/>
			</span>
			
			{{ markerList[1].id }} {{markerList[1].id }}  {{ markerList[1].longitude }} <br/>
			
			
		
			<div id="map-area">
				<google-map center="map.center" zoom="map.zoom"  options="map.options">
					<span ng-repeat="marker in markerList">
						<marker idKey="marker.id" coords="{ latitude: marker.latitude,  longitude: marker.longitude }" ></marker>
							<window show="show" isIconVisibleOnClick="true">
								<div>Latitude {{ marker.latitude }} </div>
							</window>
						
					</span>
				</google-map>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
		
		<span ng-if="trails.length >  0">
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Trail Name</th>
						<th>Location</th>
						<th>Rating</th>	
						<th>Level</th>	
						<th>Length</th>					
					</tr>
				<thead>
				<tbody>
					<tr ng-repeat="trail in trails | filter:search" >
					<td>
					<a ng-href="http://trails.greenways.ca/trails/{{ trail.name }}">{{ trail.name }}</a>
					</td>
					<td>
					{{ trail.location }} {{ trail.activity[0].name }}
					</td>
					<td>
					<span ng-repeat="i in [1, 2, 3, 4, 5]">
						<span ng-if="i <= trail.rating" >
							<span class="glyphicon glyphicon-star"></span>
						</span>
					</span>
					<!--
					{{ trail.activity.length }}
					<div ng-repeat="activity in trail.activity" >
						{{ activity.id }} - {{ activity.name }}
					</div>
					-->
					</td>
					<td>
					{{ trail.difficulty }}
					</td>	
					<td>
					{{ trail.distance | number:2 }}
					</td>	
					<td>
					<a ng-href="http://trails.greenways.ca/kml/{{ trail.kml_name }}">GPS</a>
					</td>	
					</tr>

				</tbody>
			</table>
			</div>
		</span>
		<span ng-if="trails.length ===  0">
		
		</span>
		
		
		</div>
	</div>	