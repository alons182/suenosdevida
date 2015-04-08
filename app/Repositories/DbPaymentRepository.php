<?php namespace App\Repositories;


use App\Level;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

use App\Mailers\PaymentMailer;
use App\User;

class DbPaymentRepository extends DbRepository implements PaymentRepository {

    /**
     * @var Payment
     */
    protected $model;
    /**
     * @var PaymentMailer
     */
    private $mailer;
    /**
     * @var DbUserRepository
     */
    private $userRepository;

    /**
     * @param Payment $model
     * @param PaymentMailer $mailer
     * @param UserRepository $userRepository
     */
    function __construct(Payment $model, PaymentMailer $mailer, UserRepository $userRepository)
    {
        $this->model = $model;
        $this->limit = 20;
        $this->membership_cost = 15000;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }


    /**
     * Save a payment
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $data = $this->prepareData($data);

        if ($this->existsPaymentOfMonth()) return false;
        if ($this->existsAutomaticPaymentOfMonth()) return false;


        $payment = $this->model->create($data);
        //Check level and payments for change level
        $user = $this->userRepository->findById($data['user_id']);
        $this->userRepository->checkLevel($user->parent_id);

        return $payment;

    }

    /**
     * Update a payment
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $payment = $this->model->findOrFail($id);
        $payment->fill($data);
        $payment->save();

        return $payment;
    }

    /**
     * Delete a payment by ID
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $payment = $this->findById($id);

        $payment->delete();


        return $payment;
    }


    /**
     * Get Membership cost per level
     * @return mixed
     */
    public function getMembershipCost()
    {
        $user_logged = Auth::user();
        return Level::where('level','=',$user_logged->level)->first()->payment;
    }

    /**
     * Get the possible gains per affiliates for his levels
     * @param null $data
     * @return int
     */
    public function getPossibleGainsPerAffiliates($data = null)
    {
        $user_logged = Auth::user();
        $usersOfRed = $user_logged->children()->get();
        $gainPerLevel = 0;

        foreach($usersOfRed as $user)
        {
            for ($i = 1; $i <= $user->level; $i++) {

                $paymentsOfUser = $this->model->where(function ($query) use ($data, $user)
                {
                    $query->where('user_id', '=', $user->id)
                        ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                        ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
                })->count();

                if($paymentsOfUser > 0)
                   $gainPerLevel +=  Level::where('level','=',$i)->first()->gain;

            }

        }

       return $gainPerLevel;

    }
    /**
     * Get all payments of user logged
     * @param null $data
     * @return mixed
     */
    public function getPaymentsOfUser($data = null)
    {
        $user_logged = Auth::user();

        $paymentsOfUser = $this->model->where(function ($query) use ($data, $user_logged)
        {
            $query->where('user_id', '=', $user_logged->id)
                ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->paginate($this->limit);

        return $paymentsOfUser;
    }
    /**
     * Get all payments of users of one red's user
     * @param null $data
     * @return Collection
     */
    public function getPaymentsOfUserRed($data = null)
    {
        $user_logged = Auth::user();
        $usersOfRed = $user_logged->children()->get()->lists('id');

        $paymentsOfRed = $this->model->with('users', 'users.profiles')->where(function ($query) use ($usersOfRed, $data)
        {
            $query->whereIn('user_id', $usersOfRed)
                ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->paginate($this->limit);



        return $paymentsOfRed;
    }

    /**
     * Get payments of user for the admin section
     * @param null $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPayments($data = null)
    {
        if (isset($data['q']) && ! empty($data['q']))
        {

            $usersIds = User::Search($data['q'])->get()->lists('id');

            $payments = $this->model->with('users', 'users.profiles')->where(function ($query) use ($usersIds, $data)
            {
                $query->whereIn('user_id', (count($usersIds) > 0) ? $usersIds : [0])
                    ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                    ->where(\DB::raw('YEAR(created_at)'), '=', $data['year']);
            });

        } else
        {
            $payments = $this->model->with('users', 'users.profiles')->where(function ($query) use ($data)
            {
                $query->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                    ->where(\DB::raw('YEAR(created_at)'), '=', $data['year']);
            });
        }

        return $payments->paginate($this->limit);

    }



    /**
     * Generate a payment for any user for month
     */
    public function membershipFee()
    {

        $users = User::all();
        $users_payments = 0;
        $amount = 0;


        foreach ($users as $user)
        {
            $usersOfRed = $user->children()->get()->lists('id');
            $countUsersOfRed = $user->children()->count();

            if ($countUsersOfRed)
            {
                if (! $this->existsPaymentOfMonth($user->id))
                {

                    if ($countUsersOfRed == 1)
                    {

                        $amount = $this->userOfRedPayments($usersOfRed);


                    } else if ($usersOfRed > 1)
                    {

                        $membership_cost =  Level::where('level','=',$user->level)->first()->payment;




                        $amount = ($this->userOfRedPayments($usersOfRed) > $membership_cost) ? $membership_cost : $this->userOfRedPayments($usersOfRed);
                       // $possible_gain = ($amount < $membership_cost) ? 0 : $amount; //- 5000;


                    }

                    if ($amount > 0)
                    {
                        $this->model->create([
                            'user_id'         => $user->id,
                            'payment_type'    => "MA",
                            'amount'          => $amount,
                            'bank'            => 'Pago de membresía Automático del nivel ',
                            'transfer_number' => 'Pago de membresía Automático del nivel ',
                            'transfer_date'   => Carbon::now()
                        ]);
                    }

                    $users_payments ++;

                    //$this->mailer->sendPaymentsMembershipMessageTo($user);
                }
            }


        }

        $this->mailer->sendReportMembershipMessageTo($users->count(), $users_payments);


    }

    private function userOfRedPayments($usersOfRed)
    {
        $paymentsOfRed = $this->model->where(function ($query) use ($usersOfRed)
        {
            $query->whereIn('user_id', $usersOfRed)
                ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->subMonth()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', (Carbon::now()->month == 1) ? Carbon::now()->subyear()->year : Carbon::now()->year);
        });

        //if($usersOfRed[0]==15)
        //     dd($paymentsOfRed->get()->toArray());
        $amount = $paymentsOfRed->sum(\DB::raw('amount'));

        return $amount;
    }

    /**
     * @param $data
     * @return array
     */
    private function prepareData($data)
    {

        if (! isset($data['user_id']) || ! $data['user_id'] || $data['user_id'] == "")
        {
            $data = array_except($data, 'user_id');
            $data = array_add($data, 'user_id', Auth::user()->id);
        }
        if($data['payment_type'] == "M1" || $data['payment_type'] == "M")
            $data = array_add($data, 'amount', 15000);
        if($data['payment_type'] == "M2")
            $data = array_add($data, 'amount', 25000);
        if($data['payment_type'] == "M3")
            $data = array_add($data, 'amount', 50000);
        if($data['payment_type'] == "M4")
            $data = array_add($data, 'amount', 75000);
        if($data['payment_type'] == "M5")
            $data = array_add($data, 'amount', 100000);

        $data['payment_type'] = "M";
            //$data = array_add($data, 'amount', ($data['payment_type'] == "M") ? $this->membership_cost : 5000);

        return $data;
    }

    /**
     * Verify the payments of month for not repeat one payment
     * @param null $user_id
     * @return mixed
     */
    public function existsPaymentOfMonth($user_id = null)
    {
        $payment = $this->model->where(function ($query) use ($user_id)
        {
            $query->where('user_id', '=', ($user_id) ? $user_id : Auth::user()->id)
                ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year)
                ->where(function ($query)
                {
                    $query->where('payment_type', '=', 'M')
                        ->orWhere('payment_type', '=', 'A');
                });
        })->first();

        return $payment;
    }

    /**
     * Verify the payments of month for not repeat one payment
     * @param null $user_id
     * @return mixed
     */
    public function existsAutomaticPaymentOfMonth($user_id = null)
    {
        $countUsersOfRed = Auth::user()->children()->count();
        $payment = false;

        if ($countUsersOfRed > 1)
        {
            $payment = $this->model->where(function ($query) use ($user_id)
            {
                $query->where('user_id', '=', ($user_id) ? $user_id : Auth::user()->id)
                    ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                    ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year)
                    ->where(function ($query)
                    {
                        $query->where('payment_type', '=', 'MA');

                    });
            })->first();
        }


        return $payment;
    }


}