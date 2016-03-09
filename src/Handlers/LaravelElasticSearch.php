<?php
namespace Neokike\LaravelElasticSearch\Handlers;

use Neokike\LaravelElasticSearch\Contracts\LaravelElasticSearchInterface;

class LaravelElasticSearch implements LaravelElasticSearchInterface
{

    /**
     * @var ElasticSearchIndexManagementHandler
     */
    public $manager;
    /**
     * @var ElasticSearchIndexDocumentsFacade
     */
    public $indexer;

    public function __construct(ElasticSearchIndexManagementHandler $manager,
                                ElasticSearchIndexDocumentsHandler $indexer)
    {

        $this->manager = $manager;
        $this->indexer = $indexer;
    }


}