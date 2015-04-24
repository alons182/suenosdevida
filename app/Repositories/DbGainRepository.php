<?php namespace App\Repositories;


use App\Gain;
use App\Level;
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


    /*public function getGainsPerClick($data)
    {
        $gainOfMonth = $this->model->where(function ($query) use ($data)
        {
            $query->where('user_id', '=', Auth::user()->id)
                ->where('gain_type', '=', 'C')
                ->where('month', '=', $data['month'])
                ->where('year', '=', Carbon::now()->year);
        })->sum('amount');

        return $gainOfMonth;
    }*/
    public function getAccumulatedGains($data)
    {
        $gainOfMonth = $this->model->where(function ($query) use ($data)
        {
            $query->where('user_id', '=', Auth::user()->id)
                ->where('gain_type', '=', 'B');
                //->where('month', '=', $data['month'])
                //->where('year', '=', Carbon::now()->year);
        })->sum('amount');

        return $gainOfMonth;
    }

    /**
     * Get the possible gains per affiliates for his levels
     * @param null $data
     * @param null $user
     * @return int
     */
    public function getPossibleGainsPerAffiliates($data = null, $user = null)
    {
        $user_logged =  ($user) ? $user : Auth::user();
        $usersOfRed = $user_logged->children()->get();
        $gainPerLevel = 0;


        foreach($usersOfRed as $user)
        {
            if($user_logged->level == 1)
            {
                for ($i = 1; $i <= $user->level; $i++)
                {

                    $paymentsOfUser = Payment::where(function ($query) use ($data, $user)
                    {
                        $query->where('user_id', '=', $user->id);
                            //->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                            //->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
                    })->count();

                    if($paymentsOfUser > 0)
                        $gainPerLevel +=  Level::where('level','=',$i)->first()->gain;
                }
            }
            if($user_logged->level > 1 && $user->level > 1)
            {
                $gainPerLevel +=  Level::where('level','=',$user->level)->first()->gain;
            }


        }

        return $gainPerLevel;

    }
    public function getGainsById($id)
    {
        return $this->model->where('user_id','=',$id)->paginate($this->limit);
    }
}