<?php
namespace Neokike\LaravelElasticSearch\Contracts;

use Elasticsearch\Client;

interface ElasticSearchIndexManagementHandlerInterface
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
     * Remove everything from the index.
     *
     * @return mixed
     */
    public function clearIndex();

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


}