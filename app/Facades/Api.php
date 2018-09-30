<?php
/**
 * Created by PhpStorm.
 * User: qwq
 * Date: 24.09.18
 * Time: 23:35
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Api extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api';
    }
}
