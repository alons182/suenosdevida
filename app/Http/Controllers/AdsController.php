<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CommentRequest;
use App\Mailers\ContactMailer;
use App\Repositories\AdRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class AdsController extends Controller {

    protected $adRepository;
    /**
     * @var ContactMailer
     */
    private $mailer;

    /**
     * @param AdRepository $adRepository
     * @param ContactMailer $mailer
     */
    function __construct(AdRepository $adRepository, ContactMailer $mailer)
    {
        $this->adRepository = $adRepository;
        $this->middleware('auth');
        $this->mailer = $mailer;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $canton = Auth::user()->profiles->canton;

        $ads = $this->adRepository->getByZone($canton, Auth::user()->id);

        return View::make('ads.index')->with(compact('ads'));
	}


    /**
     * Store a newly comment of Ads.
     *
     * @param CommentRequest $request
     * @return Response
     */
	public function postComment(CommentRequest $request, $ad_id)
	{
        $data = $request->all();
        $ad = $this->adRepository->findById($ad_id);

        $data['name'] = Auth::user()->profiles->first_name;
        $data['email'] = Auth::user()->email;
        $data['ad'] = $ad->name;

        $this->mailer->comment($data);

        $this->adRepository->checkAd($ad, Auth::user()->id);

        Flash::message('Commentario enviado correctamente');

        return Redirect::route('payments.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $ad = $this->adRepository->findById($id);
       $targetDate =  Carbon::now()->addMinutes(3);

		return view::make('ads.show')->with(compact('ad','targetDate'));
	}



}
