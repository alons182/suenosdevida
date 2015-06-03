<?php namespace App\Console\Commands;

use App\Mailers\PaymentMailer;
use App\Payment;
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
     * @var PaymentMailer
     */
    private $mailer;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $userRepository
     * @param PaymentMailer $mailer
     */
	public function __construct(UserRepository $userRepository, PaymentMailer $mailer)
	{
		parent::__construct();
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $users = User::all();
        $count = 0;
        foreach ($users as $user)
        {
            $count += $this->userRepository->generateCut($user, false);
            /*$descendants = $user->immediateDescendants();
            $descendantsIds = $descendants->lists('id');

            $paymentsOfRedCount = Payment::where(function ($query) use ($descendantsIds)
            {
                $query->whereIn('user_id', $descendantsIds)
                    ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                    ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
            })->count();

            if($descendants->count() == 5 && $user->level == 3 && $descendants->sum('level') == 15 )
            {
                $this->userRepository->generateCut($user, false);
                $count++;
            }else
            {
                $count += $this->userRepository->generateCutMonthly($user);

            }*/
        }
        $this->mailer->sendReportGenerateCutMonthlyMessageTo($count);
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
