<?php

namespace AlphansoTech\LiveStreaming\Providers;

use Illuminate\Support\ServiceProvider;

class LiveStreamingServicesProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');


        $this->publishes([
            __DIR__.'/../config/livestream.php' => config_path('livestream.php')
        ], 'livestream-config');

        $this->app->singleton(LiveStreamingFunction::class, function(){
            return new LiveStreamingFunction();
        });

         $this->loadHelpers();
    }


    protected function loadHelpers()
    {
        if (file_exists($file = __DIR__.'/../Helper.php')) {
            require $file;
        }
    }
}

