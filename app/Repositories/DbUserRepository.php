<?php namespace App\Repositories;

use App\Gain;
use App\Hit;
use App\Mailers\PaymentMailer;
use App\Task;
use App\User;
use Carbon\Carbon;
use App\Payment;
use App\Role;


class DbUserRepository extends DbRepository implements UserRepository
{

    protected $model;
    /**
     * @var GainRepository
     */
    private $gainRepository;
    /**
     * @var PaymentMailer
     */
    private $mailer;


    /**
     * @param User $model
     * @param GainRepository $gainRepository
     * @param PaymentMailer $mailer
     *
     */
    function __construct(User $model, GainRepository $gainRepository, PaymentMailer $mailer)
    {
        $this->model = $model;
        $this->limit = 10;
        $this->membership_cost = 12000;
        $this->annualCharge = 5000;
        $this->gainRepository = $gainRepository;
        $this->mailer = $mailer;

    }

    /** Save the user with a blank profile and assigned a role. Also verify a bonus system
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $parent_id = $data['parent_id'];
        $data = $this->prepareData($data);
        $user = $this->model->create($data);

        $role = (isset($data['role'])) ? $data['role'] : Role::whereName('member')->first();

        $user->createProfile();
        $user->assignRole($role);

        $this->bonus($user, $parent_id);

        return $user;
    }

    /**
     * Update a user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
        $user = $this->model->findOrFail($id);

        if (!$data['parent_id'])
            $data['parent_id'] = null; //$this->prepareData($data);

        $roles[] = $data['role'];

        $user->fill($data);
        $user->save();
        $user->roles()->sync($roles);


        return $user;
    }

    /**
     * Find User with your profile by Username
     * @param $username
     * @return mixed
     */
    public function findByUsername($username)
    {
        return $this->model->with('roles')->with('profiles')->whereUsername($username)->firstOrFail();
    }

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAll($search = null)
    {

        if (!count($search) > 0) return $this->model->with('roles')->with('profiles')->paginate($this->limit);

        if (trim($search['q'])) {
            $users = $this->model->Search($search['q']);
        } else {
            $users = $this->model;
        }

        if (isset($search['active']) && $search['active'] != "") {
            $users = $users->where('active', '=', $search['active']);
        }
        if (isset($search['parent']) && $search['parent'] != "") {
            $users = $users->where('parent_id', '=', $search['parent']);
        }
        $order = (isset($search['orderBy'])) ? $search['orderBy'] : 'created_at';


        return $users->with('parent')->with('roles')->with('profiles')->orderBy('users.' . $order, 'desc')->paginate($this->limit);

    }

    /**
     * Get the last user created for the dashboard panel
     * @return mixed
     */
    public function getLasts()
    {
        return $this->model->orderBy('users.created_at', 'desc')
            ->limit(6)->get(['users.id', 'users.username']);
    }

    /**
     * Generate a report with the payments of day
     * @param $date
     * @internal param $month
     * @internal param $year
     * @return array
     */
    public function reportPaymentsByDay($date = null)
    {
        if ($date) {
            $today = array(
                Carbon::parse($date)->setTime(00, 00, 00),
                Carbon::parse($date)->setTime(23, 59, 59)
            );
        } else {
            $today = array(
                Carbon::now()->setTime(00, 00, 00),
                Carbon::now()->setTime(23, 59, 59)
            );
        }

        $payments = Payment::with('users.profiles')->where(function ($query) use ($today) {
            $query->whereBetween('created_at', $today)
                ->where('payment_type', '=', 'M');

        })->get();


        $paymentsArray = [];

        foreach ($payments as $payment) {
            $paymentArray = array(
                'id' => $payment->id,
                'Usuario Registrado' => $payment->users->created_at->toDateTimeString(),
                'Email' => $payment->users->email,
                'Nombre' => $payment->users->profiles->present()->fullname,
                'Cedula' => $payment->users->profiles->ide,
                'Cuenta' => $payment->users->profiles->number_account,
                'Monto pago' => $payment->amount,
                'Fecha del pago' => $payment->created_at->toDateTimeString(),
                'Fecha de la transferencia' => $payment->transfer_date

            );

            $paymentsArray[] = $paymentArray;
        }

        //dd($paymentsArray);

        return $paymentsArray;

    }

    /**
     * Generate a report with the user and your payments for month
     * @param $month
     * @param $year
     * @return array
     */
    public function reportPaymentsByMonth($month, $year)
    {

        $users = $this->model->with('profiles')->get();

        $usersArray = [];

        foreach ($users as $user) {


            $payment = Payment::where(function ($query) use ($user, $month, $year) {
                $query->where('user_id', '=', $user->id)
                    ->where('payment_type', '<>', 'A')
                    ->where('payment_type', '<>', 'CO')
                    ->where('month', '=', $month)//->where(\DB::raw('MONTH(created_at)'), '=', $month)
                    ->where('year', '=', $year);//->where(\DB::raw('YEAR(created_at)'), '=', $year);

            })->get()->last();

            $paymentsOfMembership = ($payment) ? $payment->amount : 0;


            $gainsOfUser = Gain::where(function ($query) use ($user, $month, $year) {
                $query->where('user_id', '=', $user->id)
                    ->where('gain_type', '=', 'B')
                    ->where('month', '=', $month)
                    ->where('year', '=', $year);
            })->sum('amount');


            $paymentsAnnual = Payment::where(function ($query) use ($user, $month, $year) {
                $query->where('user_id', '=', $user->id)
                    ->Where(function ($query) {
                        $query->where('payment_type', '=', 'A')
                            ->orWhere('payment_type', '=', 'CO');
                    })
                    ->where('month', '=', $month)//->where(\DB::raw('MONTH(created_at)'), '=', $month)
                    ->where('year', '=', $year);//->where(\DB::raw('YEAR(created_at)'), '=', $year);
            })->sum('amount');


            $userArray = array(
                'id' => $user->id,
                'Usuario Registrado' => $user->created_at->toDateTimeString(),
                'Email' => $user->email,
                'Nombre' => $user->profiles->present()->fullname,
                'Cedula' => $user->profiles->ide,
                'Cuenta' => $user->profiles->number_account,
                '# Afiliados' => $user->children()->get()->count(),
                'Ganancia Por Corte' => $gainsOfUser,
                'Pago membresia' => $paymentsOfMembership,
                'Inscripción' => $paymentsAnnual,
                'Mes' => $month,
                'Año' => $year
            );

            $usersArray[] = $userArray;

        }


        return $usersArray;

    }

    /**
     * @param $data
     * @return array
     */
    public function prepareData($data)
    {
        //if (! $data['parent_id'])
        // {
        $data = array_except($data, array('parent_id'));

        // }


        return $data;
    }


    /**
     * Verify the bonus system
     * @param $user
     * @param $parent_id
     * @internal param $data
     * @return mixed
     */
    public function bonus($user, $parent_id)
    {
        if ($parent_id) {
            $parent_user = $this->model->findOrFail($parent_id);

            if ($parent_user->depth != 0) {
                //50 limite 50
                /*if ($parent_user->immediateDescendants()->count() == 4 && $parent_user->bonus != 1) //quinto afiliado
                {
                    $parent_user->bonus = 1;
                    $parent_user->save();
                    $this->bonus($user, $parent_user->parent_id);
                } *//*else if ($parent_user->immediateDescendants()->count() == 9 && $parent_user->bonus != 2) //decimo afiliado
                {
                    $parent_user->bonus = 2;
                    $parent_user->save();
                    $this->bonus($user, $parent_user->parent_id);
                } */
                //else {

                    if($parent_user->immediateDescendants()->count() == 1000) // si el patrocinador  tiene 1000 usuario subirlo al padre
                    {
                        $this->bonus($user, $parent_user->parent_id);
                    }else{
                        $user->parent_id = $parent_user->id;
                        $user->save();
                    }

                //}
            } else {
                $user->parent_id = $parent_user->id;
                $parent_user->bonus = 1;
                $parent_user->save();
                $user->save();


            }


        }

        return $user;
    }
    /**
     * Verify the Nivel system
     * @param $userToGenerate
     * @return mixed
     * @internal param $parent_id
     * @internal param $data
     */
    /*public function checkLevel($parent_id)
    {
        if ($parent_id)
        {
            $parent_user = $this->model->findOrFail($parent_id);
            $descendants = $parent_user->immediateDescendants();

            $descendantsIds = $descendants->lists('id');

            $paymentsOfRedCount = Payment::where(function ($query) use ($descendantsIds)
            {
                $query->whereIn('user_id', $descendantsIds)
                    ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                    ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
            })->count();

            //$this->generateGainToParent($parent_user->parent_id);
                //dd('id:'.$parent_user->id.' nivel:' .$parent_user->level. 'pagos:'.$paymentsOfRedCount. 'descen:'.$descendants->count(). 'sumdesc:'.$descendants->sum('level'));
            if ($descendants->count() == 5 && $paymentsOfRedCount >= 5)
            {

                    if ($parent_user->level == 1 && $descendants->sum('level') == 5 && $paymentsOfRedCount >= 5)
                    {
                        $parent_user->level = ($parent_user->level >= 5) ? 5 : $parent_user->level + 1;
                        $parent_user->save();
                        //for($i = 1; $i <= $parent_user->level; $i++ )
                           // $this->generateGainToParent($parent_user->parent_id);


                    }
                    if ($parent_user->level == 2 && $descendants->sum('level') == 10 && $paymentsOfRedCount >= 5)
                    {
                        $parent_user->level = ($parent_user->level >= 5) ? 5 : $parent_user->level + 1;
                        $parent_user->save();
                        //for($i = 1; $i <= $parent_user->level; $i++ )
                         //   $this->generateGainToParent($parent_user->parent_id);


                    }
                    if ($parent_user->level == 3 && $descendants->sum('level') == 15 && $paymentsOfRedCount >= 5 )
                    {
                        $parent_user->level = ($parent_user->level >= 5) ? 5 : $parent_user->level + 1;
                        $parent_user->save();
                        //for($i = 1; $i <= $parent_user->level; $i++ )
                         //  $this->generateGainToParent($parent_user->parent_id);


                    }
                    if ($parent_user->level == 4 && $descendants->sum('level') == 20 && $paymentsOfRedCount >= 5 )
                    {
                        $parent_user->level = ($parent_user->level >= 5) ? 5 : $parent_user->level + 1;
                        $parent_user->save();

                         //  $this->generateGainToParent($parent_user->parent_id);


                    }
                    if ($parent_user->level == 5 && $descendants->sum('level') == 25 && $paymentsOfRedCount >= 5 && $parent_user->complete_levels == 0)
                    {
                        $parent_user->level = ($parent_user->level >= 5) ? 5 : $parent_user->level + 1;
                        $parent_user->complete_levels = 1;
                        $parent_user->save();

                          // $this->generateGainToParent($parent_user->parent_id);


                    }


                $this->checkLevel($parent_user->parent_id);
            }


        }
    }*/


    /**
     * @param $userToGenerate
     */
    public function generateAnnualCharge($userToGenerate)
    {
        $payment = Payment::create([
            'user_id' => $userToGenerate->id,
            'payment_type' => "A",
            'amount' => $this->annualCharge,
            'description' => 'Cobro de Anual',
            'bank' => '--',
            'transfer_number' => '--',
            'transfer_date' => Carbon::now(),
            'month' => Carbon::now()->month,
            'year' => Carbon::now()->year
        ]);

        $totalGain = Gain::where(function ($query) use ($userToGenerate) {
            $query->where('user_id', '=', $userToGenerate->id)
                ->where('gain_type', '=', 'B');
            //->where(\DB::raw('MONTH(created_at)'), '=', 12)
            //->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->subyear()->year);

        })->get()->last();

        if ($totalGain) {
            $totalGain->amount = ($totalGain->amount - $this->annualCharge) > 0 ? $totalGain->amount - $this->annualCharge : 0;
            $totalGain->save();

        }


    }

    public function generateCut($userToGenerate, $sendEmail)
    {
        $data['month'] = Carbon::now()->subMonth()->month;
        $usersGenerated = 0;
        $charge = 0;

        $possibleGain = $this->gainRepository->getPossibleGainsPerAffiliates($data, $userToGenerate);

        $descendants = $userToGenerate->immediateDescendants();

        $descendantsIds = $descendants->lists('id')->all();
        $paymentsOfRedCount = Payment::where(function ($query) use ($descendantsIds) {
            $query->whereIn('user_id', $descendantsIds)
                ->where('payment_type', '<>', 'A') //A - Anual
                ->where('payment_type', '<>', 'PA') // PA -Pago Adicional
                ->where('month', '=', Carbon::now()->subMonth()->month)//->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->subMonth()->month)
                ->where('year', '=', (Carbon::now()->month == 1) ? Carbon::now()->subyear()->year : Carbon::now()->year);//->where(\DB::raw('YEAR(created_at)'), '=', (Carbon::now()->month == 1) ? Carbon::now()->subyear()->year : Carbon::now()->year);

        })->count();
       // dd(Carbon::now()->subMonth()->month);
        if ($paymentsOfRedCount <= 5)
            $charge = 3000; // comision 1000 si vio mas de 75 o 3000 si vio menos 75
        if ($paymentsOfRedCount > 5 && $paymentsOfRedCount <= 10)
            $charge = 5000;
        if ($paymentsOfRedCount > 10 && $paymentsOfRedCount <= 15)
            $charge = 15000;
        if ($paymentsOfRedCount > 15 && $paymentsOfRedCount <= 20)
            $charge = 25000;
        if ($paymentsOfRedCount > 20)
            $charge = 50000;

        $paymentOfMonth = Payment::where(function ($query) use ($userToGenerate) {
            $query->where('user_id', '=', $userToGenerate->id)
                ->where('payment_type', '=', 'M') //M - Membresia
                ->where('month', '=', Carbon::now()->month)//->where(\DB::raw('MONTH(created_at)'), '=', $month)
                ->where('year', '=', Carbon::now()->year);//->where(\DB::raw('YEAR(created_at)'), '=', $year);

        })->get()->last();

        if ($paymentOfMonth){
            $charge = $charge - $paymentOfMonth->amount;
        }

        if($paymentsOfRedCount > 5)
        {
            $adsVistos = $this->getHitsPerMonth($userToGenerate, $data['month'], (Carbon::now()->month == 1) ? Carbon::now()->subyear()->year : Carbon::now()->year );

            $comision = ($adsVistos >= 120) ? 2000 : 6000; // rebaja mil si > 75 sino  3000
        }else{
            $comision = 1000;
        }

        if (($possibleGain - $charge) >= 0) {
            $payment = Payment::create([
                'user_id' => $userToGenerate->id,
                'payment_type' => "MA", //MA - membresia automatica
                'amount' => $charge,
                'description' => 'Cobro de membresia por corte',
                'bank' => '--',
                'transfer_number' => '--',
                'transfer_date' => Carbon::now(),
                'month' => Carbon::now()->month,
                'year' => Carbon::now()->year
            ]);
            if ($userToGenerate->parent_id) {
                $gain = new Gain();
                $gain->user_id = $userToGenerate->parent_id;
                $gain->description = 'Ganancia generada por cobro automatico de un hijo';
                $gain->amount = $charge;
                $gain->gain_type = 'P'; // P - Ganacia generada de un hijo
                $gain->month = Carbon::now()->month;
                $gain->year = Carbon::now()->year;
                $gain->save();
            }
            if (($possibleGain - $charge) > 0) {

                $ganancia = $possibleGain - $charge;
                //$comision = $ganancia * 0.05; // rebaja mil si > 75 sino  3000

                $payment = Payment::create([
                    'user_id' => $userToGenerate->id,
                    'payment_type' => "CO",//CO - Comision
                    'amount' => $comision,
                    'description' => 'Cobro de comision de '. $comision .' a la ganacia por corte',
                    'bank' => '--',
                    'transfer_number' => '--',
                    'transfer_date' => Carbon::now(),
                    'month' => Carbon::now()->month,
                    'year' => Carbon::now()->year
                ]);

                $gain = new Gain();
                $gain->user_id = $userToGenerate->id;
                $gain->description = 'Ganancia total generada por corte';
                $gain->amount = ($ganancia < 0 ? 0 : ($ganancia - $comision));
                $gain->gain_type = 'B'; //B - ganancia del mes
                $gain->month = Carbon::now()->month;
                $gain->year = Carbon::now()->year;
                $gain->save();


            }

            $usersGenerated++;


        }
        if ($sendEmail)
            $this->mailer->sendReportGenerateCutMessageTo($userToGenerate);


        return $usersGenerated;


    }
    /*public function generateCut($userToGenerate, $sendEmail)
    {
        $data['month'] = Carbon::now()->subMonth()->month;
        $usersGenerated =0;
        $totalGain = 0;
        $totalPaymentAuto = 0;
        $possibleGain = $this->gainRepository->getPossibleGainsPerAffiliates($data,$userToGenerate);

        //if($userToGenerate->level == 1)
       // {
            if($possibleGain <= 0)
            {
                $totalGain= Payment::where(function ($query) use ($userToGenerate)
                {
                    $query->where('user_id', $userToGenerate->id)
                        ->where('payment_type','=','M')
                        ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->subMonth()->month)
                        ->where(\DB::raw('YEAR(created_at)'), '=', (Carbon::now()->month == 1) ? Carbon::now()->subyear()->year : Carbon::now()->year);
                        /*->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                        ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
                })->sum('amount');
            }else
                $totalGain = $possibleGain;

            for ($i = 1; $i <= $userToGenerate->level; $i ++)
            {

                $totalPaymentAuto += Level::where('level', '=', $i)->first()->payment;

            }

            if(($totalGain - $totalPaymentAuto) >= 0)
            {
                for ($i = 1; $i <= $userToGenerate->level; $i ++)
                {
                    $payment = Payment::create([
                        'user_id'         => $userToGenerate->id,
                        'payment_type'    => "MA",
                        'level'           => $i,
                        'amount'          => Level::where('level', '=', $i)->first()->payment,
                        'description'     => 'Cobro de membresia del nivel ' . $i,
                        'bank'            => '--',
                        'transfer_number' => '--',
                        'transfer_date'   => Carbon::now()
                    ]);
                }
                if(($totalGain - $totalPaymentAuto) > 0)
                {
                    $gain = new Gain();
                    $gain->user_id = $userToGenerate->id;
                    $gain->description = 'Ganancia generada por corte del nivel ' . $userToGenerate->level;
                    $gain->amount = (($totalGain - $totalPaymentAuto) < 0 ? 0 : ($totalGain - $totalPaymentAuto));
                    $gain->gain_type = 'B';
                    $gain->month = Carbon::now()->month;
                    $gain->year = Carbon::now()->year;
                    $gain->save();
                }
                $usersGenerated++;
            }

        //}
        if($sendEmail)
            $this->mailer->sendReportGenerateCutMessageTo($userToGenerate);


        return $usersGenerated;
       /* $descendants = $userToGenerate->immediateDescendants();

        $descendantsIds = $descendants->lists('id');

        $gain = Gain::where(function ($query) use ($userToGenerate)
        {
            $query->where('user_id', $userToGenerate->id)
                ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->sum('amount');

        $paymentsOfRedCount = Payment::where(function ($query) use ($descendantsIds)
        {
            $query->whereIn('user_id', $descendantsIds)
                ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->count();


    }*/
    /*public function generateCut($userToGenerate, $sendEmail)
    {
        $totalPaymentAuto = 0;
        $totalGain = 0;

        for ($i = 1; $i <= $userToGenerate->level; $i ++)
        {
            $payment = Payment::create([
                'user_id'         => $userToGenerate->id,
                'payment_type'    => "MA",
                'level'           => $i,
                'amount'          => Level::where('level', '=', $i)->first()->payment,
                'description'     => 'Cobro de membresia del nivel ' . $i,
                'bank'            => '--',
                'transfer_number' => '--',
                'transfer_date'   => Carbon::now()
            ]);

            $totalPaymentAuto += Level::where('level', '=', $i)->first()->payment;

        }
        foreach ($userToGenerate->children()->get() as $user)
        {
            for ($j = 1; $j <= $user->level; $j++)
            {
                $totalGain += Level::where('level', '=',$j)->first()->gain;
            }

        }

        $gain = new Gain();
        $gain->user_id = $userToGenerate->id;
        $gain->description = 'Ganancia generada por corte';
        $gain->amount = (($totalGain - $totalPaymentAuto) < 0 ? 0 : ($totalGain - $totalPaymentAuto));
        $gain->gain_type = 'B';
        $gain->month = Carbon::now()->month;
        $gain->year = Carbon::now()->year;
        $gain->save();



        if($sendEmail)
            $this->mailer->sendReportGenerateCutMessageTo($userToGenerate);

        return $userToGenerate;


    }
    public function generateCutMonthly($userToGenerate)
    {
        $totalPaymentAuto = 0;
        $totalGain = 0;
        $usersGenerated = 0;

        if($userToGenerate->level > 1)
        {
            for ($i = 1; $i <= $userToGenerate->level-1; $i ++)
            {
                $payment = Payment::create([
                    'user_id'         => $userToGenerate->id,
                    'payment_type'    => "MA",
                    'level'           => $i,
                    'amount'          => Level::where('level', '=', $i)->first()->payment,
                    'description'     => 'Cobro de membresia del nivel ' . $i,
                    'bank'            => '--',
                    'transfer_number' => '--',
                    'transfer_date'   => Carbon::now()
                ]);

                $totalPaymentAuto += Level::where('level', '=', $i)->first()->payment;
                $totalGain += Level::where('level', '=',$i)->first()->gain * 5;
            }

            $gain = new Gain();
            $gain->user_id = $userToGenerate->id;
            $gain->description = 'Ganancia generada por corte';
            $gain->amount = (($totalGain - $totalPaymentAuto) < 0 ? 0 : ($totalGain - $totalPaymentAuto));
            $gain->gain_type = 'B';
            $gain->month = Carbon::now()->month;
            $gain->year = Carbon::now()->year;
            $gain->save();

            $usersGenerated++;

        }


        return $usersGenerated;


    }*/


    //List of patners user for the modal view of user.

    public function list_patners($value = null, $search = null)
    {

        if ($search && $value != "")
            $patners = ($value) ? $this->model->where('id', '<>', $value)->search($search)->paginate(8) : $this->model->paginate(8);
        else if ($value != "")
            $patners = ($value) ? $this->model->where('id', '<>', $value)->paginate(8) : $this->model->paginate(8);
        else
            $patners = $this->model->search($search)->paginate(8);

        return $patners;
    }

    //Hits for an user
    /**
     * @param $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getHits($user)
    {
        $hits = Task::with('ad')->where('user_id', '=', $user->id)->paginate(5);

        return $hits;
    }

    public function getHitsPerMonth($user, $month = null, $year = null)
    {
        $hits = Task::with('ad')->where(function ($query) use ($user, $month, $year)
        {
            $query->where('user_id', '=', $user->id)
                ->where(\DB::raw('MONTH(created_at)'), '=', ($month) ? $month : Carbon::now()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', ($year) ? $year : Carbon::now()->year);
        })->count();

        return $hits;
    }



}