<?php namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Http\Requests\PaymentRequest;
use App\Repositories\AdRepository;
use App\Repositories\GainRepository;
use Carbon\Carbon;

use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class PaymentsController extends Controller {

    protected $userRepository;
    /**
     * @var App\Repositories\PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var GainRepository
     */
    private $gainRepository;

    function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository, AdRepository $adRepository, GainRepository $gainRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
        $this->adRepository = $adRepository;

        $this->gainRepository = $gainRepository;

        $this->middleware('auth');
    }

    /**
     * Display a listing of the payments (balance).
     * GET /balances
     *
     * @return Response
     */
    public function index()
    {
        $data = Request::all();
        if (! isset($data['month']))
        {
            $data = array_add($data, 'month', Carbon::now()->month);
        }

        $paymentsOfUser = $this->paymentRepository->getPaymentsOfUser($data);
        $paymentsOfUserRed = $this->paymentRepository->getPaymentsOfUserRed($data);

        $canton = Auth::user()->profiles->canton;

        $ads_seen = $this->adRepository->getAdsSeenByZone($canton, Auth::user()->id);
        $ads_not_seen = $this->adRepository->getAdsNotSeenByZone($canton, Auth::user()->id);

        $hit_per_day = $this->adRepository->hit_per_day(Auth::user()->id);

        $possibleGains = $this->paymentRepository->getPossibleGainsPerAffiliates($data);
        $gains = $this->gainRepository->getGains($data);
        $membership_cost = $this->paymentRepository->getMembershipCost();
        //dd($possibleGains);
        return View::make('payments.index')->with([
            'paymentsOfUser'      => $paymentsOfUser,
            'paymentsOfUserRed'      => $paymentsOfUserRed,
            'ads_seen'      => $ads_seen,
            'ads_not_seen'      => $ads_not_seen,
            'hits_per_day'      => $hit_per_day,
            'possible_gains'      => $possibleGains,
            'gains'      => $gains,
            'membership_cost'      => $membership_cost,
            'selectedMonth' => $data['month']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /balances/create
     *
     * @return Response
     */
    public function create()
    {
        return View::make('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /balances
     *
     * @return Response
     */
    public function store(PaymentRequest $request)
    {
        $data = $request->all();
        $data['transfer_date'] = $data['transfer_date_submit'];



        if(!$this->paymentRepository->store($data))
            Flash::error('Ya existe un pago para este mes.');
        else
            Flash::message('Pago agregado correctamente');

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     * GET /red
     *
     * @return Response
     */
    public function red()
    {
        return View::make('users.red')->withMonth(Carbon::now()->month)->withYear(Carbon::now()->year);
    }


}