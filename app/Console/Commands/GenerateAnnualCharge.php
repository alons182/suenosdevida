<?php namespace App\Console\Commands;


use App\Payment;
use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateAnnualCharge extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'suenos:generatecharge';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a annual Charge';
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $userRepository
     */
	public function __construct(UserRepository $userRepository)
	{
		parent::__construct();
        $this->userRepository = $userRepository;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

            $users = User::all();
            foreach ($users as $user)
            {
                $existAnnualCharge = Payment::where(function ($query) use ($user)
                {
                    $query->where('user_id', '=', $user->id)
                        ->where('payment_type', '=', 'A')
                        ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);

                })->count();

				$dateInOneYear = $user->created_at->addYear();

				//dd($dateInOneYear->month == Carbon::now()->addYear()->subMonths(1)->month && $dateInOneYear->year == Carbon::now()->addYear()->subMonths(1)->year);

				if($dateInOneYear->month == Carbon::now()->month && $dateInOneYear->year == Carbon::now()->year)
				{

					// si no existe un cargo en el año actual y ya se le aplico un cargo de forma manual entonces generar el cargo
					if($existAnnualCharge <= 0 && $user->annual_charge == 1)
						$this->userRepository->generateAnnualCharge($user);
				}


			}
            $this->info('Annual charge done!!');



	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			/*['example', InputArgument::REQUIRED, 'An example argument.'],*/
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
