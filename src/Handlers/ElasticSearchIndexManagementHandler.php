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
     * @param $shards
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

    public function create($config = [])
    {
        if (!is_array($config))
            throw new InvalidArgumentException('the config param must be an array');

        if (!count($config)) {
            $config = $this->setConfig();
        }

        $response = $this->elasticsearch->indices()->create($config);
        return $response;
    }

    /**
     * @return array
     */
    private function setConfig()
    {
        $config = [
            'index' => $this->indexName,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => $this->shards,
                    'number_of_replicas' => $this->replicas,
                ]
            ]
        ];

        if (count($this->mappings)) {
            $config['body']['mappings'] = $this->mappings;
        }

        if (count($this->analysis)) {
            $config['body']['settings']['analysis'] = $this->analysis;
        }


        return $config;
    }

    /**
     * Remove everything from the index.
     *
     * @param null $index
     * @return mixed
     */
    public function delete($index = null)
    {
        if ($index)
            return $this->elasticsearch->indices()->delete(['index' => $index]);

        return $this->elasticsearch->indices()->delete(['index' => $this->indexName]);
    }

    /**
     * modify any index setting that is dynamic
     * @param $settings
     * @return mixed
     */
    public function putSettings($settings)
    {
        return $this->elasticsearch->indices()->putSettings($settings);
    }

    /**
     * show you the currently configured settings for one or more indexes
     * @param array $indexes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getSettings($indexes = [])
    {
        if (!is_array($indexes))
            throw new InvalidArgumentException('it must be an array of indexes names');

        $indexQty = count($indexes);

        if ($indexQty) {
            return $this->elasticsearch->indices()->getSettings(['index' => $this->indexName]);
        }

        return $this->elasticsearch->indices()->getSettings(['index' => $indexes]);

    }

    /**
     * allows you to modify or add to an existing index’s mapping.
     * @param $mappings
     * @param $type
     * @param null $index
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function putMapping($mappings, $type, $index = null)
    {
        if (!is_array($mappings))
            throw new InvalidArgumentException('it must be an mapping options array');

        $mapping = $this->configMapping($mappings, $type, $index);

        return $this->elasticsearch->indices()->putMapping($mapping);
    }

    /**
     * @param $mappings
     * @param $type
     * @param $index
     * @return array
     */
    private function configMapping($mappings, $type, $index)
    {
        if (!$index) {
            $index = $this->indexName;
        }

        $mapping = [
            'index' => $index,
            'type'  => $type,
            'body'  => $mappings
        ];
        return $mapping;
    }

    /**
     * return the mapping details about your indexes and types.
     * @param $type
     * @param null $index
     * @return mixed
     */
    public function getMappings($type = null, $index = null)
    {

        $params = $this->setGetMappingParams($type, $index);

        return $this->elasticsearch->indices()->getMapping($params);
    }

    /**
     * @param $type
     * @param $index
     * @return array
     */
    private function setGetMappingParams($type, $index)
    {
        $params = [];

        if ($index) {
            $params['index'] = $index;
        }

        if ($type) {
            $params['type'] = $type;
        }

        if (!$index && !$type) {
            $params['index'] = $this->indexName;
            return $params;
        }
        return $params;
    }

    public function __call($method, $params)
    {
        return call_user_func_array(array($this->elasticsearch->indices(), $method), $params);
    }

    /**
     * recreate index with default options in config file
     * @return mixed
     */
    public function recreate()
    {
        $this->delete($this->indexName);
        return $this->create();
    }
}
