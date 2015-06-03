<?php namespace App\Http\Controllers\Admin;

use App\Gain;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Level;
use App\Payment;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;
use Faker\Factory as Faker;


class TestController extends Controller {

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param UserRepository $userRepository
     * @param PaymentRepository $paymentRepository
     */
    function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $search = $request->all();
        //dd(count($search));
        if (! count($search) > 0)
        {
            $search['q'] = "";
        }
        $search['active'] = (isset($search['active'])) ? $search['active'] : '';
        $search['parent'] = (isset($search['parent'])) ? $search['parent'] : '';
        $search['orderBy'] = 'id';
        //dd($search);
        $users = $this->userRepository->findAll($search);

		return view('admin.tests.index')->with([
            'users' => $users,
            'search' => $search['q'],
            'parent' => $search['parent'],
            'selectedStatus' => $search['active']
        ]);
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
	public function storeUsers(Request $request)
	{
        $faker = Faker::create();

        $cant_users = ($request->input('cant_users')) ? $request->input('cant_users') : 5;
        $user_id = $request->input('user_id');

        foreach (range(1, $cant_users) as $index)
        {
            $data = [
                'username' => $faker->word . $index,
                'email' => $faker->email. $index,
                'password' => "123",
                'parent_id' => $user_id

            ];
            $this->userRepository->store($data);

        }
        Flash::message('Se crearon '.$cant_users.' usuario(s) correctamente' );

        return redirect()->route('store.admin.tests.index');
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function storePayments(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        if($from != "" && $to != "")
        {
            foreach (range($from, $to) as $index)
            {
                $data['user_id'] = $index;
                $data['payment_type'] = "M";
                $data['bank'] = 'Nacional';
                $data['description'] = 'Generado desde la pestaña Pagos';
                $data['transfer_number'] = '123';
                $data['transfer_date'] = Carbon::now();


                $this->paymentRepository->store($data);


               /* $payment = Payment::create([
                    'user_id'         => $index,
                    'payment_type'    => "M",
                    'amount'          => '3000',
                    'bank'            => 'Nacional',
                    'description'     => 'Generado desde la pestaña Pagos',
                    'transfer_number' => '123',
                    'transfer_date'   => Carbon::now()
                ]);*/

                //Check level and payments for change level
                //$user = $this->userRepository->findById($index);
                // generate gain
                //$this->paymentRepository->generateGain($user->parent_id);
                /*if($user->parent_id)
                {
                    $parent_user = User::findOrFail($user->parent_id);
                    for($i = 1; $i <= $parent_user->level; $i++ )
                    {

                        $gain = new Gain();
                        $gain->user_id = $user->parent_id;
                        $gain->description = 'Ganancia generada por pago de un hijo en nivel '. $i;
                        $gain->amount = Level::where('level', '=', $i)->first()->payment;
                        $gain->gain_type = 'P';
                        $gain->month = Carbon::now()->month;
                        $gain->year = Carbon::now()->year;
                        $gain->save();
                    }

                    $this->paymentRepository->generateGain($parent_user->parent_id);
                }*/

               // $this->userRepository->checkLevel($user->parent_id);
            }

            Flash::message('Se crearon los pagos correctamente' );


        }
        return redirect()->route('store.admin.tests.index');
    }
    public function callGenerateCut()
    {
        $gains = Gain::all();
        $payments = Payment::all();
       foreach($gains as $gain)
        {
            $gain->month -= 1;
           // $gain->created_at = $gain->created_at->subMonth();
            $gain->save();
        }
        foreach($payments as $payment)
        {

            $payment->created_at = $payment->created_at->subMonth();
            $payment->save();
        }
        $exitCode = Artisan::call('suenos:generatecut');

        Flash::message('Se genero el corte mensual correctamente' );



        return redirect()->route('store.admin.tests.index');
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->userRepository->destroy($id);

        Flash::message('User Deleted');


        return redirect()->route('store.admin.tests.index');
	}

}
