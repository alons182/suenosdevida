<?php namespace App\Http\Middleware;

use Closure;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;

class AuthByRole {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if ($this->auth->guest())
        {
            return Redirect::route('login');
        }
        if (! $this->auth->guest() and (!$this->auth->user()->hasRole('administrator') and ! $this->auth->user()->hasRole('sub-administrator') and ! $this->auth->user()->hasRole('store')))
        {
            return Redirect::home();
        }
        return $next($request);
	}

}
