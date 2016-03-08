<?php

namespace specs\Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElasticSearchIndexManagementHandlerSpec extends ObjectBehavior
{
    protected $client;
    protected $indexName;
    protected $analysis;
    protected $mappings;
    protected $shards;
    protected $replicas;

    public function __construct()
    {
        $this->indexName = 'testIndex';
        $this->shards = 1;
        $this->replicas = 0;
        $this->analysis = [
            'filter' => [
                'shingle' => [
                    'type' => 'shingle'
                ]
            ]
        ];

        $this->mappings = [
            'my_type' => [
                'properties' => [
                    'my_field' => [
                        'type' => 'string'
                    ]
                ]
            ]
        ];
    }

    function let(Client $client)
    {
        $this->client = $client;
        $this->beConstructedWith($this->client);
        $this->setIndexName($this->indexName);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler');
    }

    function it_create_an_index_with_config_params()
    {
        $this->setIndexName($this->indexName);
        $this->setAnalysis($this->analysis);
        $this->setMappings($this->mappings);
        $this->setShrads($this->shards);
        $this->setReplicas($this->replicas);

        $this->client->indices()->shouldBeCalled();

        $this->createIndex();
    }
}
