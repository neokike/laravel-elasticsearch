<?php
namespace Neokike\LaravelElasticSearch\Handlers\Traits;

trait ElasticSearchHandlerTrait
{

    /**
     * Get the underlying client.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->elasticsearch;
    }

    /**
     * Set the name of the index that should be used by default.
     *
     * @param $indexName
     *
     * @return $this
     */
    public function setIndexName($indexName)
    {
        $this->indexName = $indexName;
        return $this;
    }
}