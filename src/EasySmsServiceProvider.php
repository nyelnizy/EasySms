<?php

namespace Sguy\EasySms;

use Illuminate\Support\ServiceProvider;

class EasySmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/easysms.php' => config_path('easysms.php'),
        ],'easysms');
    }
}
