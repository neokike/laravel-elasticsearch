<?php

namespace specs\Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Namespaces\IndicesNamespace;
use Elasticsearch\Transport;
use Neokike\LaravelElasticSearch\Contracts\Searchable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElasticSearchIndexDocumentsHandlerSpec extends ObjectBehavior
{

    protected $client;
    protected $indexName;
    protected $searchableBody;
    protected $searchableType;
    protected $searchableId;
    protected $searchableObject;

    public function __construct()
    {
        $this->indexName = 'testIndex';
        $this->searchableBody = ['body' => 'test'];
        $this->searchableType = 'testType';
        $this->searchableId = 1;
    }

    function let(Client $client, Searchable $searchableObject)
    {
        $this->client = $client;
        $searchableObject->getSearchableBody()->willReturn($this->searchableBody);
        $searchableObject->getSearchableType()->willReturn($this->searchableType);
        $searchableObject->getSearchableId()->willReturn($this->searchableId);
        $this->beConstructedWith($this->client);
        $this->searchableObject = $searchableObject;
        $this->setIndexName($this->indexName);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexDocumentsHandler');
    }

    function it_set_index_name()
    {
        $this->setIndexName('testIndex')->shouldReturn($this);
    }

    function it_get_the_client()
    {
        $this->getClient()->shouldReturn($this->client);
    }

    function it_upsert_data_to_index()
    {
        $this->client->index(
            [
                'index' => $this->indexName,
                'type'  => $this->searchableType,
                'id'    => $this->searchableId,
                'body'  => $this->searchableBody,
            ]
        )->shouldBeCalled();
        $this->upsertToIndex($this->searchableObject);
    }

    function it_remove_data_from_index()
    {
        $this->client->delete(
            [
                'index' => $this->indexName,
                'type'  => $this->searchableType,
                'id'    => $this->searchableId,
            ]
        )->shouldBeCalled();
        $this->removeFromIndex($this->searchableObject);
    }

    function it_remove_data_from_index_by_type_and_id()
    {
        $this->client->delete(
            [
                'index' => $this->indexName,
                'type'  => $this->searchableType,
                'id'    => $this->searchableId,
            ]
        )->shouldBeCalled();

        $this->removeFromIndexByTypeAndId($this->searchableType, $this->searchableId);
    }
    /*
        function it_clear_an_index()
        {
            $this->client->indices()->willReturn(new IndicesNamespace(new Transport(2),'endpoint'));
            $this->client->indices()->delete(['index' => $this->indexName])->shouldBeCalled();
            $this->clearIndex();
        }
    */
}
