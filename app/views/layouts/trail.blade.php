<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<html lang="en">
<!--<![endif]-->
<head>
	@include('includes.head_trail')
</head>
<body>
<header>
@include('includes.header')
</header><!-- End header -->
<nav>
@include('includes.menu')
</nav>

<div class="container">

<div class="tp-banner-container">

   <div class="tp-banner" >
	<ul>

		<li data-transition="fade" data-slotamount="1" data-masterspeed="500" data-saveperformance="on" >
		<img src="{{ asset('images/slides/internal.jpg') }}" alt=""  data-bgposition="center top" data-kenburns="on" data-duration="12000" data-ease="Linear.easeNone" data-bgfit="115" data-bgfitend="100" data-bgpositionend="center center">
        <div class="tp-caption white_heavy_70 tp-fade fadeout tp-resizeme"
			data-x="center" data-hoffset="0"
			data-y="center" data-voffset="10"
			data-speed="500"
			data-start="500"
			data-easing="Power4.easeOut"
			data-splitin="chars"
			data-splitout="chars"
			data-elementdelay="0.05"
			data-endelementdelay="0.05"
			data-endspeed="300"
			data-endeasing="Power1.easeOut"
			style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;">
			
		</div>
		</li>

	</ul>
   </div>

	<div id="waves"></div>
</div><!-- End slider -->
@include('includes.flash')
	<div class="row">
		<div class="col-md-12">

				@yield('content')
				

		</div><!-- End col-md-12 -->
	</div><!-- End row -->
</div><!-- End container -->

	<footer>
		@include('includes.footer')
	</footer>
	
@include('includes.foot')
</body>
</html>