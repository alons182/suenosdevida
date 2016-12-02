<?php namespace App\Repositories;


use App\Gain;

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
        $this->limit = 10;
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

        //Pago adicional desde administrador que genera esa ganancia al usuario asignado
        if($data["payment_type"] == 'PA')
        {
            $payment = $this->model->create($data);
            $user = $this->userRepository->findById($data['user_id']);

            $dataGain = [
                "user_id" => $user->id,
                "description" => $data['description'],
                "amount" => $data['amount'],
                "gain_type" => 'P',
                "month" => Carbon::now()->month,
                "year" => Carbon::now()->year,
            ];
            $gain = Gain::create($dataGain);

            return $payment;
        }
        ///--------------------------------------------------------

        if ($this->existsPaymentOfMonth($data['user_id'])) return false;
        if ($this->existsAutomaticPaymentOfMonth($data['user_id'])) return false;


        $payment = $this->model->create($data);

        $payment2 = $this->model->create($data);
        $payment2->month = ($payment->month == 12) ? 1 : $payment->month + 1;
        $payment2->year = ($payment->month == 12) ? $payment->year + 1 : $payment->year;
        $payment2->save();
        $payment3 = $this->model->create($data);
        $payment3->month = ($payment2->month == 12) ? 1 : $payment2->month + 1;
        $payment3->year = ($payment2->month == 12) ? $payment2->year + 1 : $payment2->year;//$payment3->created_at = $payment3->created_at->addMonths(2);
        $payment3->save();
        $payment4 = $this->model->create($data);
        $payment4->month = ($payment3->month == 12) ? 1 : $payment3->month + 1;
        $payment4->year = ($payment3->month == 12) ? $payment3->year + 1 : $payment3->year;
        $payment4->save();

        //Generate Gain for the payment
        $user = $this->userRepository->findById($data['user_id']);
        $this->generateGain($user->parent_id);
        $this->generateGain($user->parent_id, $payment2->month, $payment2->year);
        $this->generateGain($user->parent_id, $payment3->month, $payment3->year);
        $this->generateGain($user->parent_id, $payment4->month, $payment4->year);






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
    /*public function getMembershipCost()
    {
        $user_logged = Auth::user();

        return Level::where('level', '=', $user_logged->level)->first()->payment;
    }*/


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
                ->where('month', '=', $data['month'])//->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where('year', '=',  $data['year']);//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->orderBy('created_at', 'desc')->paginate($this->limit);

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
        $usersOfRed = $user_logged->children()->get()->lists('id')->all();

        $paymentsOfRed = $this->model->with('users', 'users.profiles')->where(function ($query) use ($usersOfRed, $data)
        {
            $query->whereIn('user_id', $usersOfRed)
                ->where('payment_type', '<>', 'A')
                ->where('payment_type', '<>', 'CO')
                ->where('payment_type', '<>', 'PA')
                ->where('amount', '>', 0)
                ->where('month', '=', $data['month'])//->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where('year', '=', $data['year']);//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->orderBy('month', 'desc')->orderBy('created_at', 'desc')->paginate($this->limit);


        return $paymentsOfRed;
    }

    /**
     * Get payments of membership
     * @param null $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaymentsOfMembership($data = null)
    {
        $user_logged = Auth::user();

        $payment = $this->model->where(function ($query) use ($data, $user_logged)
        {
            $query->where('user_id', '=', $user_logged->id)
                ->where('payment_type', '<>', 'A')
                ->where('payment_type', '<>', 'CO')
                ->where('payment_type', '<>', 'PA')
                ->where('month', '=', $data['month'])//->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where('year', '=', $data['year']);//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->get()->last();

        $paymentsOfMembership = ($payment) ? $payment->amount : 0;


        return $paymentsOfMembership;
    }

    /**
     * Get last commission
     * @param null $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCommissionOfGain($data = null)
    {
        $user_logged = Auth::user();

        $payment = $this->model->where(function ($query) use ($data, $user_logged)
        {
            $query->where('user_id', '=', $user_logged->id)
                ->where('payment_type', '=', 'CO')
                ->where('month', '=', $data['month'])//->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where('year', '=', $data['year']);//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->get()->last();

        $commissionOfGain = ($payment) ? $payment->amount : 0;


        return $commissionOfGain;
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

            $usersIds = User::Search($data['q'])->get()->lists('id')->all();

            $payments = $this->model->with('users', 'users.profiles')->where(function ($query) use ($usersIds, $data)
            {
                $query->whereIn('user_id', (count($usersIds) > 0) ? $usersIds : [0])
                    ->where('month', '=', $data['month'])//->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                    ->where('year', '=', $data['year']); //->where(\DB::raw('YEAR(created_at)'), '=', $data['year']);
            });

        } else
        {
            $payments = $this->model->with('users', 'users.profiles')->where(function ($query) use ($data)
            {
                $query->where('month', '=', $data['month'])//where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                ->where('year', '=', $data['year']);//->where(\DB::raw('YEAR(created_at)'), '=', $data['year']);
            });
        }

        return $payments->orderBy('created_at','desc')->paginate($this->limit);

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
        if (! isset($data['description']) || ! $data['description'] || $data['description'] == "")
        {
            $data = array_except($data, 'description');
            $data = array_add($data, 'description', 'Generado por medio de la pestaña pagos');
        }
        if ($data['payment_type'] == "M1" || $data['payment_type'] == "M")
            $data = array_add($data, 'amount', 3000);
        if ($data['payment_type'] == "M2")
            $data = array_add($data, 'amount', 10000);
        if ($data['payment_type'] == "M3")
            $data = array_add($data, 'amount', 25000);
        if ($data['payment_type'] == "M4")
            $data = array_add($data, 'amount', 50000);
        if ($data['payment_type'] == "M5")
            $data = array_add($data, 'amount', 100000);

        $data['payment_type'] = "M";

        if(isset($data['amountAdmin'])) {
            $data['payment_type'] = "PA"; //Pago Adicional
            $data['amount'] = $data['amountAdmin'];
        }

        $data = array_add($data, 'month', Carbon::now()->month);
        $data = array_add($data, 'year', Carbon::now()->year);

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
                ->where('month','=',Carbon::now()->month)//->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where('year','=',Carbon::now()->year)//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year)
                ->where(function ($query)
                {
                    $query->where('payment_type', '=', 'M');
                    //->orWhere('payment_type', '=', 'A');
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
                    ->where('month','=',Carbon::now()->month)//->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                    ->where('year','=',Carbon::now()->year)//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year)
                    ->where(function ($query)
                    {
                        $query->where('payment_type', '=', 'MA');

                    });
            })->first();
        }


        return $payment;
    }


    /**
     * Generate Gain to user
     * @param $parent_id
     * @param null $month
     * @param null $year
     */
    public function generateGain($parent_id, $month = null, $year = null)
    {
        if ($parent_id)
        {
            $data = [
                "user_id" => $parent_id,
                "description" => 'Ganancia generada por la pestaña de pagos',
                "amount" => 3000,
                "gain_type" => 'P',
                "month" => ($month) ? $month : Carbon::now()->month,
                "year" => ($year) ? $year : Carbon::now()->year,
            ];
            $gain = Gain::create($data);

            /*$newMonth= $month - $gain->created_at->month;
            if($newMonth > 0)
            {
                $gain->created_at =$gain->created_at->addMonths($newMonth);
                $gain->save();
            }*/

            /*$gain = new Gain();
            $gain->user_id = $parent_id;
            $gain->description = 'Ganancia generada por la pestaña de pagos';
            $gain->amount = 3000;
            $gain->gain_type = 'P';
            $gain->month = ($month) ? $month : Carbon::now()->month;
            $gain->year = ($year) ? $year : Carbon::now()->year;*/



        }

    }

    /**
     * Get Annual Charge for the payment index pages
     * @return int
     */
    public function getAnnualCharge()
    {
        $user_logged = Auth::user();
        $annualCharge = $this->model->where(function ($query) use ($user_logged)
        {
            $query->where('user_id', '=', $user_logged->id)
                ->where('payment_type', '=', 'A')
                ->where('month', '=', Carbon::now()->month)//->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where('year', '=', Carbon::now()->year);//->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);

        })->get()->last();

        $charge = ($annualCharge) ? $annualCharge->amount : 0;

        return $charge;
    }



}