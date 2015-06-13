<div class="container">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-3">
			<a href="http://www.greenways.ca" id="logo">Columbia Valley Greenways Trail Alliance</a>
		</div>
		<div class="col-md-9 col-sm-9 col-xs-9">
			<!--
			<div class="pull-right hidden-xs" id="phone">Call Us <strong>1 250 341 7246</strong></div>
			-->
            <nav>
            <ul id="top_nav">
                <li><a href="#" data-toggle="modal" data-target="#weathermodal"><i class="icon-sun-1"></i> Weather forecast</a></li>
                <li class="hidden-xs">
				@if (Auth::check())
					<a href="{{ URL::to('logout') }}" id="HeadLoginView_HeadLoginStatus">Log Out {{ Auth::user()->username }}</a> 
				@else
					<a href="{{ URL::to('login') }}" id="HeadLoginView_HeadLoginStatus">Log In</a>
				@endif
				</li>
            </ul>
            </nav>
		</div>
	</div>
</div>
