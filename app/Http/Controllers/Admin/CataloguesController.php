<?php namespace App\Http\Controllers\Admin;

use App\Catalogue;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogueRequest;
use App\Shop;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Laracasts\Flash\Flash;
use App\Repositories\CatalogueRepository;
use View;

class CataloguesController extends Controller {


    /**
     * @var CategoryRepository
     */
    private $catalogueRepository;
    /**
     * @var CategoryForm
     */
    //private $categoryForm;

    function __construct(CatalogueRepository $catalogueRepository)
    {
        $this->catalogueRepository = $catalogueRepository;


        //$this->middleware('authByRoleAdmins');
    }


    /**
     * Display a listing of the resource.
     * GET /catalogues
     *
     * @return Response
     */
    public function index()
    {
        $search = Request::all();
        $search['q'] = (isset($search['q'])) ? trim($search['q']) : '';
        $catalogues = $this->catalogueRepository->getAll($search);

        return View::make('admin.catalogues.index')->with([
            'catalogues'     => $catalogues,
            'search'         => $search['q']
    

        ]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /catalogues/create
     *
     * @return Response
     */
    public function create()
    {
        
        $shops = $this->getShopsToSelect();

        return View::make('admin.catalogues.create')->withShops($shops);
    }

    /**
     * Store a newly created resource in storage.
     * POST /catalogues
     *
     * @param CategoryRequest $request
     * @return Response
     */
    public function store(CatalogueRequest $request)
    {
        $input = $request->all();

        $this->catalogueRepository->store($input);

        Flash::message('Catalogue created');

        return Redirect::route('store.admin.catalogues.index');
    }

    /**
     * Show the form for editing the specified resource.
     * GET /catalogues/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $catalogue = $this->catalogueRepository->findById($id);
       

        $shops = $this->getShopsToSelect();

        return View::make('admin.catalogues.edit')->withCatalogue($catalogue)->withShops($shops);
    }

    /**
     * Update the specified resource in storage.
     * PUT /catalogues/{id}
     *
     * @param CategoryRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(CatalogueRequest $request, $id)
    {
        $input = $request->all();

        $this->catalogueRepository->update($id, $input);

        Flash::message('Catalogue updated');

        return Redirect::route('store.admin.catalogues.index');
    }


    /**
     * Remove the specified resource from storage.
     * DELETE /catalogues/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->catalogueRepository->destroy($id);

        Flash::message('Catalogue Deleted');

        return Redirect::route('store.admin.catalogues.index');
    }


    /**
     * @return mixed
     */
    private function getShopsToSelect()
    {
        if (auth()->user()->hasrole('store')) {
            $shops = Shop::where(function ($q) {
                $q->where('published', '=', 1)
                    ->where('responsable_id', '=', auth()->user()->id);
            })->lists('name', 'id')->all();

        } else
            $shops = Shop::where('published', '=', 1)->lists('name', 'id')->all();

        return $shops;
    }

}