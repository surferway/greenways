<?php 

class PagesController extends BaseController{

	public function about()
	{
		return View::make('pages.about');
	}

}