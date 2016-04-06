<?php
namespace Neokike\LaravelElasticSearch\Facades;

use Illuminate\Support\Facades\Facade;

class IndexManagerFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'IndexManager';
    }
}