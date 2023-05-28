<?php

namespace VSolutionDev\LaravelZipper;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    public function register()
    {
        App::singleton('zipper', function () {
            return new Zipper();
        });
    }
}
