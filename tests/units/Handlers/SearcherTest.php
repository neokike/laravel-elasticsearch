<?php

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Neokike\LaravelElasticSearch\Exceptions\InvalidArgumentException;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;
use Neokike\LaravelElasticSearch\Handlers\IndexManager;
use Neokike\LaravelElasticSearch\Handlers\Searcher;
use Test\units\TestCase;

class SearcherTest extends TestCase
{

    protected $indexManagement;
    protected $indexName;
    protected $shards;
    protected $replicas;
    protected $analysis;
    protected $mappings;

    public function setUp()
    {
        $settings = [
            'index' => 'my_index',
            'body'  => [
                'settings' => [
                    'number_of_replicas' => 0,
                    'refresh_interval'   => -1
                ]
            ]
        ];

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

        $clientMock = Mockery::mock(Client::class);
        $clientMock->shouldReceive('seacrh')->andReturn(['result' => 'ok']);

        $this->searcher = new Searcher($clientMock);
    }

    /**
     * @test
     */
    public function it_performs_a_search()
    {
        $this->searcher->query(['query'=>'query']);
        $this->searcher->search();
    }
}
