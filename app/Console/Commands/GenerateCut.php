<?php namespace App\Console\Commands;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCut extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'suenos:generatecut';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate the gains for an user.';
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
            $descendants = $user->immediateDescendants();
            if($descendants->count() == 5 && $user->level == 3 && $descendants->sum('level') == 15 )
            {
                $this->userRepository->generateCut($user);
            }
        }

        $this->info('Membership paid done!!');
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
			/*['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],*/
		];
	}

}
