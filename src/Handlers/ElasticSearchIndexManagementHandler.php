<?php

namespace Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use Illuminate\Support\Facades\Config;
use Neokike\LaravelElasticSearch\Contracts\ElasticSearchIndexManagementHandlerInterface;
use Neokike\LaravelElasticSearch\Exceptions\InvalidArgumentException;
use Neokike\LaravelElasticSearch\Handlers\Traits\ElasticSearchHandlerTrait;

class ElasticSearchIndexManagementHandler implements ElasticSearchIndexManagementHandlerInterface
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

    protected $analysis;
    protected $mappings;
    protected $shards;
    protected $replicas;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function createIndex($config = [])
    {
        if (!is_array($config))
            throw new InvalidArgumentException('the config param must be an array');

        if (!count($config)) {
            $config = [
                'index' => $this->indexName,
                'body'  => [
                    'settings' => [
                        'number_of_shards'   => $this->shards,
                        'number_of_replicas' => $this->replicas,
                        'analysis'           => $this->analysis,
                    ],
                    'mappings' => $this->mappings
                ]
            ];
        }

        $response = $this->elasticsearch->indices();
            //->create($config);
        return $response;
    }

    /**
     * Remove everything from the index.
     *
     * @return mixed
     */
    public function clearIndex()
    {
        $this->elasticsearch->indices()->delete(['index' => $this->indexName]);
    }

    /**
     * Set the number of replicas of the index
     *
     * @param $replicas
     * @return mixed
     */
    public function setReplicas($replicas)
    {
        $this->replicas = $replicas;
    }

    /**
     * Set the number of shards of the index
     *
     * @param $shrads
     * @return mixed
     */
    public function setShrads($shards)
    {
        $this->shards = $shards;
    }

    /**
     * Set the analizers and filter of the index
     *
     * @param $analysis
     * @return mixed
     */
    public function setAnalysis($analysis)
    {
        $this->analysis = $analysis;
    }

    /**
     * set the mappings properties of the index
     *
     * @param $mappings
     * @return mixed
     */
    public function setMappings($mappings)
    {
        $this->mappings = $mappings;
    }
}
