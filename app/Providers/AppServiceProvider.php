<?php

namespace App\Providers;

use App\Actions\Jetstream\RemoveTeamMember;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

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
    public function boot(UrlGenerator $url)
    {
        Inertia::share('storagePath', storage_path());
        Inertia::share('publicPath', public_path());
        Inertia::share('storageUrl', asset('storage'));

        JsonResource::withoutWrapping();

        // Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
    }
}
