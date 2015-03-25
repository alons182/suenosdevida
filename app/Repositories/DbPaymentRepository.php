<?php namespace App\Repositories;


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

    function __construct(Payment $model, PaymentMailer $mailer)
    {
        $this->model = $model;
        $this->limit = 20;
        $this->membership_cost = 12000;
        $this->mailer = $mailer;
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

        return $this->model->create($data);

    }

    /**
     * Get all payments of users of one red's user
     * @param null $data
     * @return Collection
     */
    public function getPaymentsOfYourRed($data = null)
    {


        // payments for the current user logged
        $paymentsOfUser = $this->model->where(function ($query) use ($data)
        {
            $query->where('user_id', '=', Auth::user()->id)
                ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        });
        $totalPaymentOfUser = $paymentsOfUser->sum(\DB::raw('amount'));
        $paymentsOfUser = $paymentsOfUser->paginate($this->limit);

        // payments for the users from the current user logged
        $usersOfRed = Auth::user()->children()->get()->lists('id');

        if ($usersOfRed)
        {
            $paymentsOfRed = $this->model->with('users', 'users.profiles')->where(function ($query) use ($usersOfRed, $data)
            {
                $query->whereIn('user_id', $usersOfRed)
                    ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                    ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
            });

            $possible_gain = $paymentsOfRed->sum(\DB::raw('possible_gain'));
            $gain = 0;

            $membership_cost = ($paymentsOfRed->count()) ? $paymentsOfRed->first()->membership_cost : $this->membership_cost;


            $payments = $paymentsOfRed->paginate($this->limit);


        } else
        {
            $payments = [];
            $gain = 0;
            $possible_gain = 0;
            $membership_cost = $this->membership_cost;

        }

        $data = array(
            'gain_bruta'     => $possible_gain,
            'possible_gain'  => $possible_gain ,
            'gain_neta'      => $gain - $membership_cost,
            'totalPaymentOfUser'  => ($totalPaymentOfUser > $this->membership_cost ? $this->membership_cost : $totalPaymentOfUser),
            'payments'       => $payments,
            'paymentsOfUser' => $paymentsOfUser
        );

        return new Collection($data);

    }

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
     * Generate a payment for any user for month
     */
    public function membershipFee()
    {

        $users = User::all();
        $users_payments = 0;
        $amount = 0;
        $gain = 0;
        $possible_gain = 0;
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
                        $possible_gain = $amount;

                    } else if ($usersOfRed > 1)
                    {
                        $amount = ($this->userOfRedPayments($usersOfRed) > $this->membership_cost) ? $this->membership_cost : $this->userOfRedPayments($usersOfRed);
                        $possible_gain = ($amount < $this->membership_cost) ? 0 : $amount; //- 5000;


                    }

                    if ($amount > 0)
                    {
                        $this->model->create([
                            'user_id'         => $user->id,
                            'membership_cost' => $this->membership_cost,
                            'payment_type'    => "MA",
                            'amount'          => $amount,
                            'possible_gain'   => $possible_gain,
                            'gain'            => 0,
                            'bank'            => 'Pago de membresía Automático',
                            'transfer_number' => 'Pago de membresía Automático',
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
        $amount = $paymentsOfRed->sum(\DB::raw('possible_gain'));

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

        //$data = array_add($data, 'user_id', Auth::user()->id);
        $data = array_add($data, 'membership_cost', $this->membership_cost);
        $data = array_add($data, 'amount', ($data['payment_type'] == "M") ? $this->membership_cost : 5000);
        $data = array_add($data, 'possible_gain', ($data['payment_type'] == "M") ? $this->membership_cost : 0); //($this->membership_cost - 5000) : 0);

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