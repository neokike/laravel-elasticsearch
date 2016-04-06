<?php
namespace Neokike\LaravelElasticSearch\Contracts;

use Elasticsearch\Client;

interface IndexManagerContract
{

    /**
     * Get the underlying client.
     *
     * @return Client
     */
    public function getClient();

    /**
     * Set the name of the index that should be used by default.
     *
     * @param $indexName
     *
     * @return $this
     */
    public function setIndexName($indexName);

    /**
     * Set the number of replicas of the index
     *
     * @param $replicas
     * @return mixed
     */
    public function setReplicas($replicas);

    /**
     * Set the number of shards of the index
     *
     * @param $shrads
     * @return mixed
     */
    public function setShrads($shrads);

    /**
     * Set the analizers and filter of the index
     *
     * @param $analysis
     * @return mixed
     */
    public function setAnalysis($analysis);

    /**
     * set the mappings properties of the index
     *
     * @param $mappings
     * @return mixed
     */
    public function setMappings($mappings);


    /**
     * create an index
     * @param array $config
     * @return mixed
     */
    public function create($config = []);

    /**
     * Remove everything from the index.
     *
     * @param null $index
     * @return mixed
     */
    public function delete($index = null);

    /**
     * modify any index setting that is dynamic
     * @param $settings
     * @return mixed
     */
    public function putSettings($settings);

    /**
     * show you the currently configured settings for one or more indexes
     * @param array $indexes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getSettings($indexes = []);

    /**
     * allows you to modify or add to an existing index’s mapping.
     * @param $mappings
     * @param $type
     * @param null $index
     * @return mixed
     */
    public function putMapping($mappings, $type, $index = null);


    /**
     * return the mapping details about your indexes and types.
     * @param $type
     * @param null $index
     * @return mixed
     */
    public function getMappings($type = null, $index = null);


    /**
     * Call to other methods in the elasticsearch api.
     * @param $method
     * @param $parms
     * @return mixed
     */
    public function __call($method, $parms);

    /**
     * recreate index with default options in config file
     * @return mixed
     */
    public function recreate();
}
