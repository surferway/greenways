<?php

class ApiController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	
		 $trails = Trail::with('organization', 'activity', 'amenity')->get();
		 
		return Response::json([
        'trails' => $trails->toArray()
        ], 200);
		
		//return Response::json(Trail::with('organization', 'activity', 'amenity')->get());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
	
	    $trail = Trail::find($id);
  
		if (! $trail){
			return Response::json([
				'error' => [
					'message' => 'Trail does not exist'
				]
			], 404);
		}
		
		return Response::json([
		
			'trail' => $trail->toArray()
		
		], 200);
		
		//$trails = Trail::where('id', '=', $id)->firstOrFail();
		//return Response::json($trails);
		//return Response::json($id);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
