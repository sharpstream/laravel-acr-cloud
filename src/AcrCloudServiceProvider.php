<?php

namespace Sharptream\AcrCloud;

use Illuminate\Support\ServiceProvider;

/**
 * Class AcrCloudServiceProvider
 * @package Sharpstream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 */
class AcrCloudServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/../config/acr-cloud.php');
        $this->publishes([
            __DIR__. $source => config_path('acr-cloud.php'),
        ]);
    }
}