<?php namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Http\Requests\PaymentRequest;
use App\Repositories\AdRepository;
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

    function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository, AdRepository $adRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
        $this->adRepository = $adRepository;

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

        $payments = $this->paymentRepository->getPaymentsOfYourRed($data);

        $canton = Auth::user()->profiles->canton;

        $ads = $this->adRepository->getByZone($canton, Auth::user()->id);

        return View::make('payments.index')->with([
            'payments'      => $payments,
            'ads'      => $ads,
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