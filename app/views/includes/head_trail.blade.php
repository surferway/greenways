<!-- Basic Page Needs -->
<meta charset="utf-8">
<title>@yield('meta-title', 'Columbia Valley Greenways Trail Alliance')</title>
<meta name="description" content="The Columbia Valley Greenways Trail Alliance envisions the region between Canal Flats and Donald as connected by land and water greenways.">
<meta name="author" content="Columbia Valley Greenways Trail Alliance">

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('img/apple-touch-icon-57x57-precomposed.png') }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset('img/apple-touch-icon-72x72-precomposed.png') }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset('img/apple-touch-icon-114x114-precomposed.png') }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset('img/apple-touch-icon-144x144-precomposed.png') }}">

<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">

<!-- CSS -->
{{ HTML::style('css/bootstrap.min.css') }}
{{ HTML::style('css/superfish.css') }}
{{ HTML::style('css/style.css') }}
{{ HTML::style('fontello/css/fontello.css') }}
{{ HTML::style('js/fancybox/source/jquery.fancybox.css?v=2.1.4') }}
{{ HTML::style('css/weather.css') }}

<!-- Owl Carousel Assets -->
{{ HTML::style('css/owl.carousel.css') }}
{{ HTML::style('css/owl.theme.css') }}

<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<!-- SLIDER REVOLUTION -->
{{ HTML::style('css/extralayers.css') }}
{{ HTML::style('css/navstylechange.css') }}
{{ HTML::style('rs-plugin/css/settings.css') }}
{{ HTML::style('css/bootstrap-slider.min.css') }}


<script src="https://www.google.com/jsapi"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=weather"></script>
<script type="text/javascript" src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>
<script type="text/javascript" src="http://geoxml3.googlecode.com/svn/trunk/ProjectedOverlay.js"></script>
