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
        $this->membership_cost = 5000;

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
                ->where('gain_type', '=', 'B')
                ->where('month', '=', $data['month'])//->where('month', '<=', $data['month']);
                ->where('year', '=', $data['year']);
        })->sum('amount');

        $gainOfMonthPrev = 0;

        if($gainOfMonth > 0 && $gainOfMonth <= 5000)
        {
             $gainOfMonthPrev = $this->model->where(function ($query) use ($data)
            {
                $query->where('user_id', '=', Auth::user()->id)
                    ->where('gain_type', '=', 'B')
                    ->where('month', '=', ($data['month'] == 1) ? 12 : $data['month'] - 1)//$data['month'])//->where('month', '<=', $data['month']);
                    ->where('year', '=', ($data['month'] == 1) ? $data['year'] - 1 : $data['year']);
            })->sum('amount');

        }
       
        return $gainOfMonth + $gainOfMonthPrev;
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
        $gain = 0;
        $gain = $this->model->where(function ($query) use ($data,$user_logged)
        {
            $query->where('user_id', '=', $user_logged->id)
                ->where('gain_type', '=', 'P')
                ->where('month', '=', $data['month'])
                ->where('year', '=', $data['year']);
        })->sum('amount');

        return $gain;

    }
   /* public function getPossibleGainsPerAffiliates($data = null, $user = null)
    {
        $user_logged =  ($user) ? $user : Auth::user();
        $usersOfRed = $user_logged->children()->get();
        $gainPerLevel = 0;


        $gainPerLevel = $this->model->where(function ($query) use ($data,$user_logged)
        {
            $query->where('user_id', '=', $user_logged->id)
                ->where('from_level_change', '=', ($user_logged->level > 1) ? 0 : 0)
                ->where('gain_type', '=', 'P')
                ->where('month', '=', $data['month'])
                ->where('year', '=', Carbon::now()->year);
        })->sum('amount');

        foreach($usersOfRed as $user)
        {

           /* if($user_logged->level == 1 && $user->level == 1)
            {
                for ($i = 1; $i <= $user->level; $i++)
                {

                    $paymentsOfUser = Payment::where(function ($query) use ($data, $user)
                    {
                        $query->where('user_id', '=', $user->id)
                            ->where(\DB::raw('MONTH(created_at)'), '=', $data['month'])
                            ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
                    })->count();

                    if($paymentsOfUser > 0)
                        $gainPerLevel +=  Level::where('level','=',$i)->first()->gain;
                }
            }
            if($user_logged->level == 2 && $user->level == 2)
            {
                for ($j = 1; $j <= $user->level; $j++)
                {
                    $gainPerLevel += Level::where('level', '=', $j)->first()->gain;
                }
            }
            if($user_logged->level == 3 && $user->level == 3)
            {
                for ($j = 1; $j <= $user->level; $j++)
                {
                    $gainPerLevel += Level::where('level', '=', $j)->first()->gain;
                }
            }
            if($user_logged->level == 4 && $user->level == 4)
            {
                for ($j = 1; $j <= $user->level; $j++)
                {
                    $gainPerLevel += Level::where('level', '=', $j)->first()->gain;
                }
            }
            if($user_logged->level == 5 && $user->level == 5)
            {
                for ($j = 1; $j <= $user->level; $j++)
                {
                    $gainPerLevel += Level::where('level', '=', $j)->first()->gain;
                }
            }


        }

        return $gainPerLevel;

    }*/
    public function getGainsById($id)
    {
        return $this->model->where(function ($query) use ($id)
        {
            $query->where('user_id', '=', $id)
                ->where('gain_type', '=', 'B');

        })->orderBy('created_at','DESC')->paginate($this->limit);
    }
}