<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Payment;
use App\Repositories\UserRepository;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Faker\Factory as Faker;


class TestController extends Controller {

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.tests.index');
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

        return redirect()->back();
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
                $payment = Payment::create([
                    'user_id'         => $index,
                    'payment_type'    => "M",
                    'amount'          => '15000',
                    'bank'            => 'Nacional',
                    'description'     => 'Generado desde la pestaña Pagos',
                    'transfer_number' => '123',
                    'transfer_date'   => Carbon::now()
                ]);

                //Check level and payments for change level
                $user = $this->userRepository->findById($index);

                $this->userRepository->checkLevel($user->parent_id);
            }

            Flash::message('Se crearon los pagos correctamente' );


        }
        return redirect()->back();
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
