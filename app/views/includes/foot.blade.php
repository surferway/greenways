<div id="toTop">Back to top</div>

<!-- Weather modal -->
<div class="modal fade" id="weathermodal" tabindex="-1" role="dialog" aria-labelledby="myWeathermodal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myWeathermodal">Weather Forecast</h4>
			</div>
			<div class="modal-body">
				<div id="weather" class="clearfix">
				</div>
			</div>
		</div>
	</div>
</div><!-- End Weather modal -->

<!-- JQUERY
{{ HTML::script('js/jquery-1.10.2.min.js') }}
 -->
{{ HTML::script('js/jquery-1.11.1.min.js') }}

<!-- REVOLUTION SLIDER-->     
{{ HTML::script('rs-plugin/js/jquery.themepunch.plugins.min.js') }}
{{ HTML::script('rs-plugin/js/jquery.themepunch.revolution.min.js') }}
{{ HTML::script('js/slider_func_innerpage.js') }} 

<!-- OTHER JS -->    
{{ HTML::script('js/superfish.js') }}
{{ HTML::script('js/retina.min.js') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/jquery.zweatherfeed.min.js') }}
{{ HTML::script('js/jquery.placeholder.js') }}
{{ HTML::script('js/jquery.form.js') }}
{{ HTML::script('js/functions.js') }}
{{ HTML::script('js/ajax.js') }}


<!-- CAROUSEL   -->
{{ HTML::script('js/owl.carousel.min.js') }}

<script>
// $(document).ready(function(){
		// "use strict";
		// $(".carousel").owlCarousel({
		// items : 1,
		// singleItem:true,
		// responsive:true,
		// autoHeight : true,
		// transitionStyle:"fade"
	// });
// });

$(document).ready(function() {
 
  $("#owl-demo").owlCarousel({
 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
 
      items : 6,
      //itemsDesktop : [1199,3],
      //itemsDesktopSmall : [979,3]
 
  });
 
});
</script>

<!-- FANCYBOX -->
{{ HTML::script('js/fancybox/source/jquery.fancybox.pack.js?v=2.1.4') }}
{{ HTML::script('js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5') }}
{{ HTML::script('js/fancy_func.js') }}
{{ HTML::script('js/bootstrap-slider.min.js') }}

@yield('scripts')