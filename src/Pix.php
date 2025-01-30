<?php

namespace LiveOficial\Pix;

use Illuminate\Support\Facades\Facade;

class Pix extends Facade
{
    public static function getFacadeAccessor()
    {
        return PixService::class;
    }
}
