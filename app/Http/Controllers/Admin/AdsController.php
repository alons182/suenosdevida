<?php namespace App\Http\Controllers\Admin;

use App\Ad;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Http\Requests\AdRequest;
use App\Repositories\AdRepository;
use App\Repositories\GalleryAdRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;


class AdsController extends Controller {

    protected  $adRepository;
    /**
     * @var GalleryAdRepository
     */
    private $galleryAdRepository;

    /**
     * @param AdRepository $adRepository
     * @param GalleryAdRepository $galleryAdRepository
     */
    function __construct(AdRepository $adRepository, GalleryAdRepository $galleryAdRepository)
    {
        $this->adRepository = $adRepository;
        $this->galleryAdRepository = $galleryAdRepository;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $search = Request::all();
        $search['q'] = (isset($search['q'])) ? trim($search['q']) : '';
        $search['published'] = (isset($search['published'])) ? $search['published'] : '';

        $ads = $this->adRepository->getAll($search);

        return View::make('admin.ads.index')->with([
            'ads'         => $ads,
            'search'           => $search['q'],
            'selectedStatus'   => $search['published']

        ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.ads.create');
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param AdRequest $request
     * @return Response
     */
	public function store(AdRequest $request)
	{
		$data = $request->all();

        $this->adRepository->store($data);

        Flash::message('Ad Created');

        return redirect::route('store.admin.ads.index');
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $ad = $this->adRepository->findById($id);

        return View::make('admin.ads.edit')->with(compact('ad'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param AdRequest $request
     * @param  int $id
     * @return Response
     */
	public function update(AdRequest $request, $id)
	{
		$data = $request->all();

        $this->adRepository->update($id,$data);

        Flash::message('Ad Updated');

        return redirect::route('store.admin.ads.index');
	}

    /**
     * published a Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function pub($id)
    {
        $this->adRepository->update_state($id, 1);

        return Redirect::route('store.admin.ads.index');
    }

    /**
     * Unpublished a Ad.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function unpub($id)
    {
        $this->adRepository->update_state($id, 0);

        return Redirect::route('store.admin.ads.index');
    }

    /**
     * Featured.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function feat($id)
    {
        $this->adRepository->update_feat($id, 1);

        return Redirect::route('store.admin.ads.index');
    }

    /**
     * un-featured.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function unfeat($id)
    {
        $this->adRepository->update_feat($id, 0);

        return Redirect::route('store.admin.ads.index');
    }


    /**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->adRepository->destroy($id);

        Flash::message('Ad Deleted');

        return Redirect::route('store.admin.ads.index');
	}

    /**
     * Store a newly created resource in storage.
     * POST /photos
     *
     * @return Response
     */
    public function storeImage()
    {
        $data['ad_id'] = Request::get('id');
        $data['photo'] = $_FILES['file'];

        return $this->galleryAdRepository->store($data);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /photos/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroyImage($id)
    {
        return $this->galleryAdRepository->destroy($id);
    }

}
