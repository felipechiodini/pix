<?php

namespace LiveOficial\Pix;

use Illuminate\Support\Facades\Facade;

class PixFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return PixService::class;
    }
}
