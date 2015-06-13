@extends('layouts.default')

@section('scripts')
  {{HTML::script('js/jquery.PrintArea.js')}}

  <script type="text/javascript">
    $(function(){
	
	  $("#printqr").click(function(){
		$("div#PrintAreaQR").printArea( );
	  });
	  
    });
  </script>
@stop	  

@section('content')
	
	<p>The Columbia River Greenways Alliance (CRGA/ Greenways) is a non-profit organization, based out of the Columbia Valley, that has been working to develop a network of trails to connect our communities since 2000.</p>
	<p>The <a href="http://www.greenways.ca/about-crg/board-of-directors/">CRGA Board of Directors</a> collaborates with landowners, local government, industry and other interests to achieve community green space connectivity. Our mission is to develop a network of linear corridors of open natural places that maintain the wildlife habitat and recreation legacy of the region, enhancing the ecological, economic, and community well-being of Upper Columbia Valley.</p>
	<p>The resulting network of trails will ultimately stretch from Donald (north of Golden) to the source of the Columbia River at Canal Flats.</p>
	
	<a href="http://www.greenways.ca/how-can-you-help/make-a-donation/" target="_blank">Make a Donation QR</a>
	<br/>
	<div id="qrprint">
		<img src="https://api.qrserver.c
		om/v1/create-qr-code/?data={{ rawurlencode("http://www.greenways.ca/how-can-you-help/make-a-donation/")}}&size=100x100" alt="" title="" width="100" />
	</div>
	<a href="#" role="button" class="btn" data-toggle="modal" data-target="#qrmodal">Enlarge QR to print</a>	
	
	
	<h3><strong>Our Vision</strong></h3>
	<p>The Columbia River Greenways Alliance envisions the region between Canal Flats and Donald, BC as connected by land and water greenways. Our efforts are aimed at enhancing interconnections within the greenway, so that we may continue to enjoy the Columbia Valley as a thriving eco-district with healthy ecology, community and economy.</p>
	<p>The CRGA’s overall goal is to link the communities of the Village of Canal Flats, BC to Donald, BC (west of Golden, BC) by a trail network for non-motorized forms of transport. The CRGA is currently working on a number of projects/ trail sections in various stages of completion. Trail sections will be connected to make up the <a href="http://www.greenways.ca/trails/the-upper-columbia-trail-network/"><strong>Upper Columbia Trail Network.</strong> </a>The result will form a system of landscape elements that form a linked ecological network, providing increased support to previously isolated patches of the valley.</p>
	<h3><strong>Elements of the Upper Columba Trail Network</strong></h3>
	<p><strong>Spine Trail:</strong> The trail system begins with a ‘spine trail’ which will form a primary route, and will extend along the length of the Columbia River Valley. The spine trail will serve to connect current trails with new routes where required to provide a safe transportation corridor for Columbia River Valley Residents and visitors. The trail network will also provide greenways corridors that will link existing nodes of ecological significance as well as connecting and highlighting regions of cultural and historical importance.</p>
	<p><strong>Nodes or hubs:</strong> Areas of ecological or cultural significance. These may require special protection or restoration, and could also include educational signs or brochures. The linking of nodes by connecting trails will be emphasized.</p>
	<p><strong>Paths or links:</strong> Non-motorised corridors that enable people and animals to travel within the greenway. Corridors may be animal migration routes, historic human travel paths, rivers and creeks, old roads, and man-made trails with boardwalks.</p>
	<p>CRGA efforts are aimed at enhancing and restoring these connections, so that we may continue to enjoy the Columbia Valley as a thriving eco-district with healthy ecology, community and economy.</p>
	<p><strong>Greenway Restoration/ Enhancements: </strong>Habitat restoration is a critical aspect of our work, focusing on natural elements that have suffered degradation through human use. Restoration actions are prescribed by ecologists, and where possible, CRGA includes educational materials regarding site enhancements. Riparian restoration can include creation of channels and fish barriers to regenerate fish habitat.</p>
	<p>Outreach CRGA uses historic anecdotes, maps, graphics and photos in our interpretative panels, which are positioned strategically throughout the Columbia River Greenway. We seek to engage visitors with the local history and to orient them to the greenway. We endeavour to meet the needs of a diverse audience – from the serious naturalist, hiker, paddler to the novice. We also strive to provide local landowners with helpful stewardship information.</p>
	<p></p>
	<h3><strong>Our Values</strong></h3>
	<p>- Integration of human use and social well-being with ecological gains</p>
	<p>- Stewardship through public education</p>
	<p>- Achievement through collaboration</p>
	<p>- Solutions that resolve conflict</p>
	<p>- Accountability to our partners and our communities</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	

<!-- QR modal -->
<div class="modal fade" id="qrmodal" tabindex="-1" role="dialog" aria-labelledby="myQRmodal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myImagemodal">Make a Donation</h4>
			</div>
			<div class="modal-body">
				<div id="PrintAreaQR">
	<img src="https://api.qrserver.c
			om/v1/create-qr-code/?data={{ rawurlencode("http://www.greenways.ca/how-can-you-help/make-a-donation/")}}&size=300x300" alt="" title=""  />
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