<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
#binding
use App\Gain;
use App\Hit;
use App\Task;
use App\User;
use Carbon\Carbon;

App::bind('App\Repositories\UserRepository', 'App\Repositories\DbUserRepository');
App::bind('App\Repositories\PaymentRepository', 'App\Repositories\DbPaymentRepository');
App::bind('App\Repositories\CategoryRepository', 'App\Repositories\DbCategoryRepository');
App::bind('App\Repositories\ProductRepository', 'App\Repositories\DbProductRepository');
App::bind('App\Repositories\PhotoRepository', 'App\Repositories\DbPhotoRepository');
App::bind('App\Repositories\OrderRepository', 'App\Repositories\DbOrderRepository');
App::bind('App\Repositories\AdRepository', 'App\Repositories\DbAdRepository');
App::bind('App\Repositories\GainRepository', 'App\Repositories\DbGainRepository');
App::bind('App\Repositories\ShopRepository', 'App\Repositories\DbShopRepository');
App::bind('App\Repositories\GalleryAdRepository', 'App\Repositories\DbGalleryAdRepository');
App::bind('App\Repositories\CatalogueRepository', 'App\Repositories\DbCatalogueRepository');
/**
 * Pages
 */
Route::get('/', [
    'as'   => 'home',
    'uses' => 'PagesController@index'
]);
Route::get('about', [
    'as'   => 'about',
    'uses' => 'PagesController@about'
]);
Route::get('opportunity', [
    'as'   => 'opportunity',
    'uses' => 'PagesController@opportunity'
]);
Route::get('aid-plan', [
    'as'   => 'aid',
    'uses' => 'PagesController@aid'
]);
Route::get('contact', [
    'as'   => 'contact',
    'uses' => 'PagesController@contact'
]);
Route::post('contact', [
    'as'   => 'contact.store',
    'uses' => 'PagesController@postContact'
]);
Route::get('terms', [
    'as'   => 'terms',
    'uses' => 'PagesController@terms'
]);
Route::get('descargas', [
    'as'   => 'downloads_path',
    'uses' => 'DownloadsController@index'
]);

/**
 * Registration
 */
Route::get('register', [
    'as'   => 'registration.create',
    'uses' => 'RegistrationController@create',
    'middleware' => 'guest'
]);

Route::post('register', [
    'as'   => 'registration.store',
    'uses' => 'RegistrationController@store',
    'middleware' => 'guest'
]);

/**
 * Authentication
 */
Route::get('login', [
    'as'   => 'login',
    'uses' => 'SessionsController@create'
]);
Route::get('logout', [
    'as'   => 'logout',
    'uses' => 'SessionsController@destroy'
]);
Route::resource('sessions', 'SessionsController', [
    'only' => ['create', 'store', 'destroy']
]);

/**
 * Ads user
 */
Route::post('ads/comment/{ad}', [
    'as'   => 'ads.comment',
    'uses' => 'AdsController@postComment'
]);

Route::get('ads/type/{type}', [
    'as'   => 'ads_type',
    'uses' => 'AdsController@adsByType'
]);
Route::post('ads/viewed/{ad}', [
    'as'   => 'ads.viewed',
    'uses' => 'AdsController@postViewed'
]);

Route::resource('ads', 'AdsController');
/**
 * Payments user
 */
Route::post('payments/cashing', [
    'as'   => 'payments.cashing',
    'uses' => 'PaymentsController@postCashing'
]);

Route::resource('payments', 'PaymentsController');

/**
 * orders user
 */

Route::resource('orders', 'OrdersController');

/**
 * Cart
 */
Route::get('cart', [
    'as'   => 'cart_path',
    'uses' => 'OrdersController@cart'
]);

Route::get('cart/checkout', [
    'as'   => 'cart_checkout',
    'uses' => 'OrdersController@formCheckout',
    'middleware' => 'auth'
]);

Route::post('cart/checkoutConfirm', [
    'as'   => 'cart_checkout.confirm',
    'uses' => 'OrdersController@formPostCheckout',
    'middleware' => 'auth'
]);


Route::post('cart/checkout', [
    'as'   => 'cart_checkout.store',
    'uses' => 'OrdersController@store',
    'middleware' => 'auth'
]);


/**
 * Members Red
 */
Route::get('red', [
    'as'   => 'red.show',
    'uses' => 'PaymentsController@red',
    'middleware' => 'auth'
]);
/**
 * Profile
 */
Route::resource('profile', 'ProfilesController', [
    'only' => ['show', 'edit', 'update']
]);

Route::get('/{profile}', [
    'as'   => 'profile.register',
    'uses' => 'RegistrationController@create',
    'middleware' => 'guest'
]);

/**
 * Administration Store
 */
Route::group(['prefix' => 'store/admin', 'middleware' => 'authByRole'], function ()
{

    # Dashboard
    Route::get('/', [
        'as'   => 'dashboard',
        'uses' => 'Admin\DashboardController@index'

    ]);

    # Users
    Route::get('users', [
        'as'   => 'users',
        'uses' => 'Admin\UsersController@index'

    ]);
    Route::get('users/register', [
        'as'   => 'user_register',
        'uses' => 'Admin\UsersController@create'
    ]);
    Route::post('users/register', [
        'as'   => 'user_register.store',
        'uses' => 'Admin\UsersController@store'
    ]);
    foreach (['active', 'inactive'] as $key)
    {
        Route::post('users/{user}/' . $key, array(
            'as'   => 'users.' . $key,
            'uses' => 'Admin\UsersController@' . $key,
        ));
    }
    Route::post('users/charge/{user}', [
        'as'   => 'users.annual_charge',
        'uses' => 'Admin\UsersController@callGenerateCharge'
    ]);
    Route::get('users/gainsExcel', [
        'as'   => 'users_gains_excel',
        'uses' => 'Admin\UsersController@exportGainsList'
    ]);
    Route::get('users/paymentsDayExcel', [
        'as'   => 'users_payments_excel',
        'uses' => 'Admin\UsersController@exportPaymentsList'
    ]);
    Route::get('users/list', [
        'as' => 'patners_list',
        'uses' => 'Admin\UsersController@list_patners'
    ]);

    Route::resource('users', 'Admin\UsersController');
    # gains
     Route::resource('gains', 'Admin\GainsController',['only' => ['destroy']]);
    # hits
    Route::resource('hits', 'Admin\HitsController',['only' => ['destroy']]);

    # shops

    foreach (['pub', 'unpub'] as $key)
    {
        Route::post('shops/{shop}/' . $key, [
            'as'   => 'shops.' . $key,
            'uses' => 'Admin\ShopsController@' . $key,
        ]);
    }
    Route::get('shops', [
        'as'   => 'shops',
        'uses' => 'Admin\ShopsController@index'
    ]);
    Route::get('shops/list', [
        'as' => 'shops_list',
        'uses' => 'Admin\ShopsController@list_shops'
    ]);
    Route::post('shops/reply', [
        'as'   => 'shops_reply',
        'uses' => 'Admin\ShopsController@reply'
    ]);
    Route::resource('shops', 'Admin\ShopsController');

    # categories
    foreach (['up', 'down', 'pub', 'unpub', 'feat', 'unfeat'] as $key)
    {
        Route::post('categories/{category}/' . $key, [
            'as'   => 'categories.' . $key,
            'uses' => 'Admin\CategoriesController@' . $key,
        ]);
    }
    Route::get('categories', [
        'as'   => 'categories',
        'uses' => 'Admin\ProductsController@index'
    ]);
    Route::get('categories/list', [
        'as' => 'categories_list',
        'uses' => 'Admin\CategoriesController@list_categories'
    ]);
    Route::resource('categories', 'Admin\CategoriesController');

    # products

    foreach (['pub', 'unpub', 'feat', 'unfeat'] as $key)
    {
        Route::post('products/{product}/' . $key, array(
            'as'   => 'products.' . $key,
            'uses' => 'Admin\ProductsController@' . $key,
        ));
    }
    Route::post('products/delete', [
        'as'   => 'destroy_multiple',
        'uses' => 'Admin\ProductsController@destroy_multiple'
    ]);
    Route::get('products/list', [
        'as'   => 'products_list',
        'uses' => 'Admin\ProductsController@list_products'
    ]);
    Route::get('products', [
        'as'   => 'products',
        'uses' => 'Admin\ProductsController@index'
    ]);

    Route::resource('products', 'Admin\ProductsController');

    #catalogues
    Route::resource('catalogues', 'Admin\CataloguesController');

    #photos
    Route::post('photos', [
        'as'   => 'save_photo',
        'uses' => 'Admin\PhotosController@store'
    ]);
    Route::post('photos/{photo}', [
        'as'   => 'delete_photo',
        'uses' => 'Admin\PhotosController@destroy'
    ]);

    #orders
    Route::post('orders/delete', [
        'as'   => 'destroy_multiple_orders',
        'uses' => 'Admin\OrdersController@destroy_multiple'
    ]);
    Route::get('orders/list', [
        'as'   => 'orders_list',
        'uses' => 'Admin\OrdersController@list_orders'
    ]);
    Route::get('orders', [
        'as'   => 'orders',
        'uses' => 'Admin\OrdersController@index'
    ]);

    Route::resource('orders', 'Admin\OrdersController');

    Route::post('downloads/store', [
        'as'   => 'downloads_store_path',
        'uses' => 'Admin\DownloadsController@store'
    ]);
    Route::delete('downloads/{image}', [
        'as'   => 'downloads_delete_path',
        'uses' => 'Admin\DownloadsController@destroy'
    ]);
    Route::resource('downloads', 'Admin\DownloadsController');

    #banners
    Route::post('banners/store', [
        'as'   => 'banners_store_path',
        'uses' => 'Admin\BannersController@store'
    ]);
    Route::delete('banners/{image}', [
        'as'   => 'banners_delete_path',
        'uses' => 'Admin\BannersController@destroy'
    ]);
    Route::resource('banners', 'Admin\BannersController');
    # payments
    Route::resource('payments', 'Admin\PaymentsController');

    # ads
    foreach (['up', 'down', 'pub', 'unpub', 'feat', 'unfeat'] as $key)
    {
        Route::post('ads/{ad}/' . $key, [
            'as'   => 'ads.' . $key,
            'uses' => 'Admin\AdsController@' . $key,
        ]);
    }
    Route::get('ads', [
        'as'   => 'ads',
        'uses' => 'Admin\AdsController@index'
    ]);

    Route::post('ads/photos', [
        'as'   => 'save_ad_photo',
        'uses' => 'Admin\AdsController@storeImage'
    ]);
    Route::post('ads/photos/{photo}', [
        'as'   => 'delete_ad_photo',
        'uses' => 'Admin\AdsController@destroyImage'
    ]);

    Route::resource('ads', 'Admin\AdsController');

    #Test controller
    Route::post('tests/store_users', [
        'as'   => 'store_users',
        'uses' => 'Admin\TestController@storeUsers'
    ]);
    Route::post('tests/store_payments', [
        'as'   => 'store_payments',
        'uses' => 'Admin\TestController@storePayments'
    ]);
    Route::post('tests/generatecut', [
        'as'   => 'generate_cut',
        'uses' => 'Admin\TestController@callGenerateCut'
    ]);
    Route::post('tests/generatecharge/{user_id}', [
        'as'   => 'generate_charge',
        'uses' => 'Admin\TestController@callGenerateCharge'
    ]);
    route::get('tests/passday',function ()
        {
            $tasks = Task::all();
            $hits = Hit::all();
            foreach($tasks as $task)
            {
                $task->created_at =$task->created_at->subDay();
                $task->save();
            }
            foreach($hits as $hit)
            {
                $hit->created_at = $hit->created_at->subDay();
                $hit->hit_date = $hit->created_at;
                $hit->save();
            }


        });
    Route::get('tests/paymentscount', function(){

        $parent_user = User::findOrFail(2);
        $descendants = $parent_user->immediateDescendants();

        $descendantsIds = $descendants->lists('id')->all();

        $paymentsOfRedCount = Gain::where(function ($query) use ($descendantsIds)
        {
            $query->whereIn('user_id', $descendantsIds)
                ->where('month', '=', Carbon::now()->month)
                ->where('year' , '=', Carbon::now()->year);
            /*->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
            ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);*/
        })->count();
        dd($paymentsOfRedCount);
    });

    Route::resource('tests', 'Admin\TestController');
});
Route::group(['prefix' => 'store'], function ()
{
    # products
    Route::get('categories/{category}/products', [
            'as'   => 'products_path',
            'uses' => 'ProductsController@index']
    );
    Route::get('categories/{category}/products/{product}', [
        'as'   => 'product_path',
        'uses' => 'ProductsController@show'
    ]);
    Route::get('search', [
        'as' => 'products_search',
        'uses' => 'ProductsController@search'
    ]);

    # categories
    Route::get('categories', [
            'as'   => 'categories_path',
            'uses' => 'ProductsController@categories']
    );

    # shop
    Route::get('cantones', [
            'as'   => 'cantones_path',
            'uses' => 'ShopsController@cantones']
    );
    Route::get('{canton}/shops', [
            'as'   => 'shops_path',
            'uses' => 'ShopsController@index']
    );
    Route::get('shops/{shop}', [
            'as'   => 'shop_path',
            'uses' => 'ShopsController@show']
    );

    #catalogues
     Route::post('shops/{shop}/catalogues/request', [
            'as'   => 'catalogues.resquest',
            'uses' => 'CataloguesController@request']
    );


});



Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);





