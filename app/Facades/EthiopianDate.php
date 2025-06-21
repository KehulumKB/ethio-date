<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EthiopianDate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ethiopian-date';
    }
}
