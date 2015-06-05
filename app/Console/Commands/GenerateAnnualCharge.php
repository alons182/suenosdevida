<?php namespace App\Console\Commands;

use App\Payment;
use App\Repositories\UserRepository;
use App\User;
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
        $existAnnualCharge = Payment::where('payment_type', '=', 'A')->count();

        if($existAnnualCharge > 0)
        {
            $users = User::all();
            foreach ($users as $user)
            {
                $this->userRepository->generateAnnualCharge($user);
            }
            $this->info('Annual charge done!!');
        }


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
