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

        $this->middleware('auth', ['only' => [
            'postComment',
            'postViewed',
        ]]);

        $this->mailer = $mailer;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        return View::make('ads.index');

        /*return View::make('ads.index')->with(compact('ads'));*/
	}
    public function adsByType($type)
    {

        if(Auth::user()) {
            $canton = Auth::user()->profiles->canton;
            $province = Auth::user()->profiles->province;

            $ads = $this->adRepository->getAds($province, $canton, Auth::user()->id, $type);

            $hits_per_day = $this->adRepository->hits_per_day(Auth::user()->id, $type);
            $hits_per_week = $this->adRepository->hits_per_week(Auth::user()->id, $type);

        }else{

            $ads = $this->adRepository->getAdsPublic($type);

            $hits_per_day = 0;
            $hits_per_week = 0;
        }




        // para mostrar de que dia empieza y termina la semana
        $week = Carbon::now()->weekOfMonth;



        $dayOfWeek = (Carbon::now()->dayOfWeek == Carbon::SUNDAY ) ? 7 : Carbon::now()->dayOfWeek;


        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $today = Carbon::now()->today();

        return View::make('ads.ads')->with([
            'ads' => $ads,
            'hits_per_day' => $hits_per_day,
            'hits_per_week' => $hits_per_week,
            'week' => $week,
            'startOfWeek' => $startOfWeek->toDateString(),
            'endOfWeek' => $endOfWeek->toDateString(),
            'dayOfWeek' => $dayOfWeek,
            'today' => $today->toDateString()
        ]);
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
        $data['ad_email'] = $ad->email;

        $this->adRepository->checkAd($ad, Auth::user()->id);

        $this->mailer->comment($data);


        Flash::message('Comentario enviado correctamente');

        return Redirect::route('ads.index');
	}
    public function postViewed(Request $request, $ad_id)
    {
        $data = $request->all();
        $ad = $this->adRepository->findById($ad_id);

        $data['name'] = Auth::user()->profiles->first_name;
        $data['email'] = Auth::user()->email;
        $data['ad'] = $ad->name;
        $data['ad_email'] = $ad->email;
        $data['comment'] = 'Sitio Web';

        $this->adRepository->checkAd($ad, Auth::user()->id);

        $this->mailer->comment($data);

        return 'web vista';
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
        $targetDate =  Carbon::now()->addMinutes(1);
        $hits_per_day = 0;
        $hits_per_week = 0;

        if(Auth::user()) {
            $hits_per_day = $this->adRepository->hits_per_day(Auth::user()->id, $ad->ad_type);
            $hits_per_week = $this->adRepository->hits_per_week(Auth::user()->id, $ad->ad_type);
        }
		return view::make('ads.show-'.$ad->ad_type)->with(compact('ad','targetDate','hits_per_day', 'hits_per_week'));
	}



}
