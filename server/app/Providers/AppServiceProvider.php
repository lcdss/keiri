<?php

namespace App\Providers;

use App\Models\Media;
use Faker\{Factory, Generator};
use Spatie\MediaLibrary\MediaObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Media::observe(MediaObserver::class);

        Relation::morphMap([
            'media' => 'App\Models\Media',
            'transactions' => 'App\Models\Transaction',
        ]);

        $this->app->singleton(Generator::class, function () {
            return Factory::create('pt_BR');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
