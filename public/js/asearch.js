 var app = angular.module('SearchApp',['google-maps']);
 
 // app.config(function ($interpolateProvider) {
   // $interpolateProvider.startSymbol('[[');
   // $interpolateProvider.endSymbol(']]');
// })


 app.controller('TrailsController', function($scope, $http){

	$http.get('/api/v1/alltrails').success(function(trails){
		$scope.trails = trails;
	});
	
	$http.get('/api/v1/allactivity').success(function(activities){
		$scope.activities = activities;
	});
	
	$scope.map = {
		center: {
			latitude: 50.508368,
			longitude: -116.031534
		},
		zoom: 8,
		options: {
			mapTypeId: google.maps.MapTypeId.TERRAIN,
			scrollwheel: false
		}
		
	};
	$scope.options = {scrollwheel: false};
	$scope.marker = {
		id:0,
		coords: {
			latitude: 50.508368,
			longitude: -116.031534
		},
		icon: "http://trails.greenways.ca/images/activities/marker-hike.png",
		options: { draggable: true },
		events: {
			dragend: function (marker, eventName, args) {
				$log.log('marker dragend');
				$log.log(marker.getPosition().lat());
				$log.log(marker.getPosition().lng());
			}
		}
    };
	$scope.markerList = [
		{ id: 1, latitude: 50, longitude: -116 },
		{ id: 2, latitude: 50.504931, longitude: -116.028322	},
		{ id: 3, latitude: 51.440222, longitude: -116.355715	}
	];
	
	$scope.useActivity = [];
	
	$scope.filterActivity = function () {
        return function (p) {
            for (var i in $scope.useActivity) {
                if (p.make == $scope.group[i] && $scope.useActivity[i]) {
                    return true;
                }
            }
        };
    };
	


 });
 
 