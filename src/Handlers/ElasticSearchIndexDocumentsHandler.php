<?php
namespace Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use Neokike\LaravelElasticSearch\Contracts\ElasticSearchIndexDocumentsHandlerInterface;
use Neokike\LaravelElasticSearch\Contracts\Searchable;
use Neokike\LaravelElasticSearch\Handlers\Traits\ElasticSearchHandlerTrait;

class ElasticSearchIndexDocumentsHandler implements ElasticSearchIndexDocumentsHandlerInterface
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
     * Add or update the given searchable subject to the index.
     *
     * @param Searchable $subject
     */
    public function upsertToIndex(Searchable $subject)
    {
        $this->elasticsearch->index(
            [
                'index' => $this->indexName,
                'type'  => $subject->getSearchableType(),
                'id'    => $subject->getSearchableId(),
                'body'  => $subject->getSearchableBody(),
            ]
        );
    }

    /**
     * Remove the given subject from the search index.
     *
     * @param Searchable $subject
     */
    public function removeFromIndex(Searchable $subject)
    {
        $this->elasticsearch->delete(
            [
                'index' => $this->indexName,
                'type'  => $subject->getSearchableType(),
                'id'    => $subject->getSearchableId(),
            ]
        );
    }

    /**
     * Remove an item from the search index by type and id.
     *
     * @param string $type
     * @param int $id
     */
    public function removeFromIndexByTypeAndId($type, $id)
    {
        $this->elasticsearch->delete(
            [
                'index' => $this->indexName,
                'type'  => $type,
                'id'    => $id,
            ]
        );
    }
}