<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('ticket_redemption', function (Request $request) {
            if(config('app.env') === 'local') {
                return Limit::perHour(60)->by($request->user()?->id ?: $request->ip());
            } elseif(config('app.env') === 'testing') {
                // we are using the user's name to ensure we get a different value for every test
                // this way, we would not have to worry about rate limiter key clashes and having to reset the rate limiter
                return Limit::perHour(5)->by($request->user()?->name);
            } else {
                return Limit::perHour(200)->by($request->user()?->id ?: $request->ip());
            }
        });
    }
}
