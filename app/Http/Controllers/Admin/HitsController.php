<?php namespace App\Http\Controllers\Admin;

use App\Hit;
use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class HitsController extends Controller {


	public function destroy($id)
	{
		$task = Task::find($id);

		$hit = Hit::find($task->hit_id);

		if($task)
			$task->delete();
		if($hit)
			$hit->delete();

        Flash::message('Click Eliminado');

        return Redirect::back();
	}

}
