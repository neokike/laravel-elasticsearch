<?php
namespace Neokike\LaravelElasticSearch\Facades;

use Illuminate\Support\Facades\Facade;

class ElasticSearchIndexManagementFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'ElasticSearchIndexManagementFacade';
    }
}