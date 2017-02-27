<?php namespace App\Providers;


use App\Category;
use App\Shop;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
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
         view()->composer('shops/show', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('layouts/partials._banner', function($view){

            $path = dir_banners_path();

            File::exists($path) or File::makeDirectory($path);
            $files =File::files($path);
            $filesArray = [];

            foreach ($files as $file)
            {
                $fileArray = array(
                    'type' => File::extension($file),
                    'name'  => explode("//",$file)[1]

                );
                $filesArray[] = $fileArray;
            }



            $view->with('files', new Collection($filesArray));
        });


        /* view()->composer('layouts/partials._list_categories', function($view){
            $view->with('categories', Category::where('depth', '=','0')->get());
        });
        */

        // admin
        view()->composer('admin/dashboard/index', function($view){
            $view->with('currentUser', Auth::user());
        });
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
        view()->composer('admin/catalogues/index', function($view){
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
        view()->composer('admin/users/edit', function($view){
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
        /*view()->composer('layouts/partials._navbarSite', function($view){
            $view->with('categories', Category::where('depth', '=','0')->get());
        });*/
        view()->composer('layouts/partials._navbarSite', function($view){
           $view->with('shops', Shop::where('published', '=','1')->get());
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
        view()->composer('admin/shops/index', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/shops/edit', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/shops/partials._form', function($view){
            $view->with('currentUser', Auth::user());
        });
        view()->composer('admin/shops/partials._reply', function($view){
            $view->with('shops', Shop::where('published','=', 1)->lists('name','id')->all());
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
