<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use Carbon\Carbon;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;

class PaymentsController extends Controller {

    protected $userRepository;
    /**
     * @var App\Repositories\PaymentRepository
     */
    private $paymentRepository;


    function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;


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
        if (! isset($data['year']))
        {
            $data = array_add($data, 'year', Carbon::now()->year);
        }
        $data['q'] = (isset($data['q'])) ? trim($data['q']) : '';

        $payments = $this->paymentRepository->getPayments($data);

        return View::make('admin.payments.index')->with([
            'payments'      => $payments,
            'selectedMonth' => $data['month'],
            'selectedYear' => $data['year'],
            'search'           => $data['q']
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
        return View::make('admin.payments.create');
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
        $data['user_id'] =  $data['user_id_payment'];
        $data['transfer_date'] = $data['transfer_date_submit'];


        if(!$this->paymentRepository->store($data))
            Flash::error('Ya existe un pago para este mes.');
        else
            Flash::message('Pago agregado correctamente');

        return Redirect::back();
    }

    /**
     * Show the form for editing the specified resource.
     * GET /payments/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $payment = $this->paymentRepository->findById($id);

        return View::make('admin.payments.edit')->withPayment($payment);
    }

    /**
     * Update the specified resource in storage.
     * PUT /payments/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

       if(Request::input('name') == 'amount')
       {
           $data['amount'] = Request::input('value');
       }else
       {
          $data['gain'] = Request::input('value');
       }
        
        $this->paymentRepository->update($id, $data);

        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /payment/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->paymentRepository->destroy($id);

        Flash::message('Payment Deleted');

        return Redirect::route('store.admin.payments.index');
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