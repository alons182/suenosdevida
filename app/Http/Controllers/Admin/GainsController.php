<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\GainRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class GainsController extends Controller {

    /**
     * @var GainRepository
     */
    private $gainRepository;

    /**
     * @param GainRepository $gainRepository
     */
    function __construct(GainRepository $gainRepository)
    {
        $this->gainRepository = $gainRepository;
    }


    public function destroy($id)
    {
        $this->gainRepository->destroy($id);

        Flash::message('Ganancia Eliminada');

        return Redirect::back();
    }

}
