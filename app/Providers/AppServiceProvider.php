<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $userLogged = auth()->check();
        // dd($userLogged);
        // if(isset($userLogged) && Auth::user()->role == 1) {
        //     Order::all()->where('sent', '=', '0')->count();
        //     View::share('ordersChecking', $ordersChecking);
        // }

        // if(isset($userLogged) && Auth::user()->role == 0) {
        //     $user = Auth::user()->email;
        //     $ordersChecking = Order::all()->where('mailuser', '=', $user)->where('sent', '=', 0)->count();
        // }


    }
}
