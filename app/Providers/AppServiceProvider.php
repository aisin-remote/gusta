<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
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
        \Illuminate\Pagination\Paginator::defaultView('pagination.default');
        
        Gate::define('approver', function(User $user){
            return $user->role === 'approver';
        });

        Gate::define('visitor', function(User $user){
            return $user->role === 'visitor';
        });
        
        Gate::define('admin', function(User $user){
            return $user->role === 'admin';
        });

        Gate::define('GA', function(User $user){
            return $user->role === 'GA';
        });
    }
}