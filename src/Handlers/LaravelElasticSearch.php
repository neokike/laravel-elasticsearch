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
     * @var ElasticSearchIndexDocumentsHandler
     */
    public $indexer;
    /**
     * @var ElasticSearchSearchHandler
     */
    public $searcher;

    public function __construct(ElasticSearchIndexManagementHandler $manager,
                                ElasticSearchIndexDocumentsHandler $indexer,
                                ElasticSearchSearchHandler $searcher)
    {

        $this->manager = $manager;
        $this->indexer = $indexer;
        $this->searcher = $searcher;
    }

    /**
     * index an Searchable object or a collection of them
     * @param $documents
     * @return mixed
     */
    public function indexDocuments($documents)
    {
        // TODO: Implement indexDocuments() method.
    }

    /**
     * delete an Searchable object or a collection of them
     * @param $documents
     * @return mixed
     */
    public function deleteDocuments($documents)
    {
        // TODO: Implement deleteDocuments() method.
    }


    /**
     * Perform a search
     * @param $query
     * @return mixed
     */
    public function search($query)
    {
        return $this->searcher->search($query);
    }
}