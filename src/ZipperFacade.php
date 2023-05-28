<?php

namespace VSolutionDev\LaravelZipper;

use Illuminate\Support\Facades\Facade;

class ZipperFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'zipper';
    }
}
