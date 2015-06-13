<?php
class PrintController extends \BaseController 
{
    public function testPDF()
    {
        $pdf = PDF::loadHTML('<h1>Hello World!!</h1>');
        return $pdf->stream();
    }
	
	public function trailPDF()
    {
	
		$id = 29;
		$trail = Trail::findOrFail($id);
		
		$html = View::make('trails/show_pdf', ['trail' => $trail]);
		
		return $html;
		
        $pdf = PDF::loadView('trails/show_pdf', ['trail' => $trail]);
		//$pdf = PDF::loadHTML($html);
        return $pdf->stream();
    }
	
}