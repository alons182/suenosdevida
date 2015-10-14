<?php namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Http\Requests\PaymentRequest;
use App\Mailers\ContactMailer;
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

class PaymentsController extends Controller
{

    protected $userRepository;
    /**
     * @var App\Repositories\PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var GainRepository
     */
    private $gainRepository;
    /**
     * @var ContactMailer
     */
    private $mailer;

    /**
     * @param UserRepository $userRepository
     * @param PaymentRepository $paymentRepository
     * @param AdRepository $adRepository
     * @param GainRepository $gainRepository
     * @param ContactMailer $mailer
     */
    function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository,
                         AdRepository $adRepository, GainRepository $gainRepository, ContactMailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
        $this->adRepository = $adRepository;

        $this->gainRepository = $gainRepository;

        $this->middleware('auth');
        $this->mailer = $mailer;
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
        if (!isset($data['month'])) {
            $data = array_add($data, 'month', Carbon::now()->month);
        }

        $paymentsOfUser = $this->paymentRepository->getPaymentsOfUser($data);
        $paymentsOfUserRed = $this->paymentRepository->getPaymentsOfUserRed($data);
        $paymentsOfMembership = $this->paymentRepository->getPaymentsOfMembership($data);
        $commissionOfGain = $this->paymentRepository->getCommissionOfGain($data);
        $canton = Auth::user()->profiles->canton;

        $ads = $this->adRepository->getAds($canton, Auth::user()->id);

        $hits_per_day = $this->adRepository->hits_per_day(Auth::user()->id);
        $hits_per_week = $this->adRepository->hits_per_week(Auth::user()->id);

        $possibleGains = $this->gainRepository->getPossibleGainsPerAffiliates($data);

        $accumulatedGains = $this->gainRepository->getAccumulatedGains($data);

        //$membership_cost = $this->paymentRepository->getMembershipCost();

        // $annualCharge = $this->paymentRepository->getAnnualCharge();

        // para mostrar de que dia empieza y termina la semana
        $week = Carbon::now()->weekOfMonth;


       // $startOfMonth = Carbon::now()->startOfMonth();
       // $startOfWeekDefault = Carbon::now()->startOfWeek();
       // $endOfWeekDefault = Carbon::now()->endOfWeek();


       // $differenceDays = ($startOfMonth->diffInDays($startOfWeekDefault));

        //$startOfWeek = $startOfWeekDefault->subDays($differenceDays);
        //$endOfWeek = $endOfWeekDefault->subDays($differenceDays);
        $dayOfWeek = (Carbon::now()->dayOfWeek == Carbon::SUNDAY ) ? 7 : Carbon::now()->dayOfWeek;
        $dt = Carbon::create(2015, 9, 22, 12, 0, 0);
        //dd($dt->weekOfMonth. '-'.$dt->dayOfWeek);
        //dd($dt->dayOfWeek /*== Carbon::MONDAY*/);
        //dd($dayOfWeek);

        return View::make('payments.index')->with([
            'paymentsOfUser' => $paymentsOfUser,
            'paymentsOfUserRed' => $paymentsOfUserRed,
            'paymentsOfMembership' => $paymentsOfMembership,
            'commission' => $commissionOfGain,
            'ads' => $ads,
            'hits_per_day' => $hits_per_day,
            'hits_per_week' => $hits_per_week,
            'week' => $week,
            'possible_gains' => $possibleGains,
            'accumulatedGains' => $accumulatedGains,
            //'membership_cost'   => $membership_cost,
            'selectedMonth' => $data['month'],
            //'startOfWeek' => $startOfWeek->toDateString(),
            //'endOfWeek' => $endOfWeek->toDateString(),
            'dayOfWeek' => $dayOfWeek
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
     * @param PaymentRequest $request
     * @return Response
     */
    public function store(PaymentRequest $request)
    {
        $data = $request->all();
        $data['transfer_date'] = $data['transfer_date_submit'];


        if (!$this->paymentRepository->store($data))
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

    /**
     * Cashing of gains for email.
     * GET /cashing
     *
     * @return Response
     */
    public function postCashing()
    {
        $data = Auth::user()->toArray();
        $this->mailer->cashing($data);
        Flash::message('Se envio un correo al administrador solicitado tu retiro de ganancias');
        return Redirect::route('payments.index');
    }


}