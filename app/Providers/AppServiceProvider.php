<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RedirectIfNotAdmin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->routes(function () {
        //     Route::middleware('web')
        //         ->group(base_path('routes/web.php'));
    
        //     // Register the middleware here
        //     Route::middleware('auth')->middleware(RedirectIfNotAdmin::class)
        //         ->group(function () {
        //             // Define admin-specific routes
        //             Route::get('/admin/dashboard', function () {
        //                 return view('admin.dashboard');
        //             })->name('admin.dashboard');
        //         });
        // });
    }
}
