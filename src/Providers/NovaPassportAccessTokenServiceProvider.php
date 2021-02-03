<?php
namespace R64\NovaPassportAccessTokens\Providers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use R64\NovaPassportAccessTokens\Http\Middleware\Authorize;

class NovaPassportAccessTokenServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'nova-passport-manager');

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-passport-manager')
            ->group(__DIR__.'/../../routes/api.php');
    }

}