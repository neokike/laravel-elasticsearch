<?php
namespace Neokike\LaravelElasticSearch\Facades;

use Illuminate\Support\Facades\Facade;

class DocumentIndexerFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'DocumentIndexer';
    }
}