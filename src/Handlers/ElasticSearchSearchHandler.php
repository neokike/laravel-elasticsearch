<?php

namespace Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use Neokike\LaravelElasticSearch\Collections\ElasticSearchCollection;
use Neokike\LaravelElasticSearch\Contracts\ElasticSearchSearchHandlerInterface;
use Neokike\LaravelElasticSearch\Handlers\Traits\ElasticSearchHandlerTrait;

class ElasticSearchSearchHandler implements ElasticSearchSearchHandlerInterface
{
    use ElasticSearchHandlerTrait;

    /**
     * @var Client
     */
    protected $elasticsearch;
    /**
     * @var string
     */
    protected $indexName;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * it performs a search
     *
     * @param $query
     * @return mixed
     */
    public function search($query)
    {
        if (!array_has($query, 'index'))
            $query['index'] = $this->indexName;

        $results = $this->elasticsearch->search($query);

        return new ElasticSearchCollection($results);
    }
}
