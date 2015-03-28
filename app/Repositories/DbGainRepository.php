<?php namespace App\Repositories;


use App\Gain;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DbGainRepository extends DbRepository implements GainRepository {

    /**
     * @var Payment
     */
    protected $model;
    /**
     * @var PaymentMailer
     */
    private $mailer;

    function __construct(Gain $model)
    {
        $this->model = $model;
        $this->limit = 20;
        $this->membership_cost = 12000;

    }


    public function getGains($data)
    {
        $gainOfMonth = $this->model->where(function ($query) use ($data)
        {
            $query->where('user_id', '=', Auth::user()->id)
                ->where('month', '=', $data['month'])
                ->where('year', '=', Carbon::now()->year);
        })->sum('amount');

        return $gainOfMonth;
    }
}