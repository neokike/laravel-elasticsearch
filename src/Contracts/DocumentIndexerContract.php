<?php
namespace Neokike\LaravelElasticSearch\Contracts;

use Elasticsearch\Client;

interface DocumentIndexerContract
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
     * Add or update the given searchable subject to the index.
     *
     * @param Searchable $subject
     */
    public function upsertToIndex(Searchable $subject);

    /**
     * Remove the given subject from the search index.
     *
     * @param Searchable $subject
     */
    public function removeFromIndex(Searchable $subject);

    /**
     * Remove an item from the search index by type and id.
     *
     * @param string $type
     * @param int $id
     */
    public function removeFromIndexByTypeAndId($type, $id);

}