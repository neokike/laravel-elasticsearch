<?php
namespace Neokike\LaravelElasticSearch\Contracts;

use Elasticsearch\Client;

interface ElasticSearchSearchHandlerInterface
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
     * it performs a search
     *
     * @param $query
     * @return mixed
     */
    public function search($query);
}
