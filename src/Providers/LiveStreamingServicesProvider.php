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
        

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../views', 'LiveStream');
        
         $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
            __DIR__.'/../config/livestream.php' => config_path('livestream.php'),
            __DIR__.'/../public/js' => public_path('vendor/livestream/js'),
            __DIR__.'/../public/css' => public_path('vendor/livestream/css'),
            __DIR__.'/../public/wowza' => public_path('vendor/livestream/wowza'),
        ], 'livestream-assets');
 

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

