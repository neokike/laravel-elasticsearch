<?php
namespace Neokike\LaravelElasticSearch\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelElasticSearchFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'Neokike\LaravelElasticSearch\Handlers\LaravelElasticSearch';
    }
}