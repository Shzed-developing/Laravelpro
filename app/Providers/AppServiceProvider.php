<?php

namespace App\Providers;

use App\Features\VideoPlayerV2;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

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
        Feature::define('video-player-v2', VideoPlayerV2::class);

        EnsureFeaturesAreActive::whenInactive(
            function (Request $request, array $features) {
                return new Response(status: 403);
            }
        );
    }
}
