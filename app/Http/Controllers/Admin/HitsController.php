<?php namespace App\Http\Controllers\Admin;

use App\Hit;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class HitsController extends Controller {


	public function destroy($id)
	{
		$hit = Hit::destroy($id);

        Flash::message('Click Eliminado');

        return Redirect::back();
	}

}
