<?php

namespace VSolutionDev\LaravelZipper;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ZipperServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::singleton('zipper', function () {
            return new Zipper();
        });
    }
}
