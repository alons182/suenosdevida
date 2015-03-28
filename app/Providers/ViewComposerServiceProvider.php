<?php namespace App\Providers;


use App\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		view()->composer('layouts/partials._navbarSite', function($view){
           $view->with('currentUser', Auth::user());
        });
        view()->composer('layouts/partials._navbarAccount', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('users/red', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('orders/checkout', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('payments/index', function($view){
            $view->with('currentUser', Auth::user());
        });

        // admin
        view()->composer('admin/layouts/partials._navbar', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/products/partials._form', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/products/index', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/categories/index', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/users/partials._export', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/users/partials._export', function($view){
            $view->with('currentUser', Auth::user())
            ->with('currentMonth', Carbon::now()->month)
            ->with('currentYear', Carbon::now()->year);

        });
        view()->composer('admin/users/index', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/users/partials._form', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/payments/partials._form', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/payments/index', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/orders/edit', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/orders/index', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('layouts/partials._navbarSite', function($view){
            $view->with('categories', Category::where('depth', '=','0')->get());
        });
        view()->composer('layouts/partials._footer', function($view){
            $view->with('categories', Category::where('depth', '=','0')->get());
        });
        view()->composer('admin/ads/index', function($view){
            $view->with('currentUser', Auth::user());
        });

        view()->composer('admin/ads/partials._form', function($view){
            $view->with('currentUser', Auth::user());
        });
        //View::share('currentUser', Auth::user());
       // View::share('currentMonth', Carbon::now()->month);
      //  View::share('currentYear', Carbon::now()->year);
       // View::share('categories', Category::where('depth', '=','0')->get());
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
