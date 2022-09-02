<?php


namespace App\Providers;


use App\Core\Infrastructure\Clients\Strapi\Http\Client;
use App\Core\Infrastructure\Services\ApiTranslator\ApiTranslator;
use App\Core\Infrastructure\Services\ApiTranslator\CachedApiTranslator;
use Illuminate\Cache\Repository;
use Illuminate\Support\ServiceProvider;

class StrapiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $endpoint = env('STRAPI_ENDPOINT', '');
            $identifier = env('STRAPI_IDENTIFIER', '');
            $password = env('STRAPI_PASSWORD', '');
            return new Client($endpoint, $identifier, $password);
        });

        $this->app->singleton(ApiTranslator::class, function ($app) {
            return new ApiTranslator($app->make(Client::class));
        });

        $this->app->singleton(CachedApiTranslator::class, function ($app) {
            return new CachedApiTranslator($app->make(ApiTranslator::class), $app->make(Repository::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
