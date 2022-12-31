<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Storage;
use Storage;
class AssetController extends Controller
{
	public function __invoke($file){
		// abort_if(auth()->guest(), Response::HTTP_FORBUDDEN);

		// echo $file;die();
		$path = "secret/".$file."";
		// dd($path);
		return response()->file(
			Storage::path($path)
		);
	}
}
?>