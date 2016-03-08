<?php
namespace Neokike\LaravelElasticSearch\Facades;

use Illuminate\Support\Facades\Facade;

class ElasticSearchIndexDocumentsFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'ElasticSearchIndexDocuments';
    }
}