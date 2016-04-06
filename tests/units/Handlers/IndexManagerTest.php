<?php

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Neokike\LaravelElasticSearch\Exceptions\InvalidArgumentException;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;
use Neokike\LaravelElasticSearch\Handlers\IndexManager;
use Test\units\TestCase;

class IndexManagerTest extends TestCase
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

        $indicesMock = Mockery::mock(IndicesNamespace::class);
        $indicesMock->shouldReceive('create')->andReturn(['result' => 'ok']);
        $indicesMock->shouldReceive('delete')->andReturn(['result' => 'ok']);
        $indicesMock->shouldReceive('putSettings')->withAnyArgs()->andReturn(['result' => 'ok']);
        $indicesMock->shouldReceive('getSettings')->withAnyArgs()->andReturn($settings);
        $indicesMock->shouldReceive('putMapping')->withAnyArgs()->andReturn(['result' => 'ok']);
        $indicesMock->shouldReceive('getMapping')->withAnyArgs()->andReturn(['result' => 'ok']);
        $indicesMock->shouldReceive('analyze')->withAnyArgs()->andReturn(['result' => 'ok']);

        $clientMock = Mockery::mock(Client::class);
        $clientMock->shouldReceive('indices')->andReturn($indicesMock);

        $this->indexManagement = new IndexManager($clientMock);
    }

    /**
     * @test
     */
    public function it_create_an_index()
    {

        $response = $this->indexManagement->create();
        $this->assertEquals(['result' => 'ok'], $response);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if__create_an_index_config_param_is_not_an_array()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $response = $this->indexManagement->create('hola');
    }

    /**
     * @test
     */
    public function it_set_the_config_params()
    {
        $this->indexManagement->setIndexName($this->indexName);
        $this->indexManagement->setAnalysis($this->analysis);
        $this->indexManagement->setMappings($this->mappings);
        $this->indexManagement->setShrads($this->shards);
        $this->indexManagement->setReplicas($this->replicas);
        $config = $this->invokeMethod($this->indexManagement, 'setConfig');

        $expected = [
            'index' => $this->indexName,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => $this->shards,
                    'number_of_replicas' => $this->replicas,
                    'analysis'           => $this->analysis,
                ],
                'mappings' => $this->mappings
            ]
        ];
        $this->assertEquals($expected, $config);
    }

    /**
     * @test
     */
    public function it_delete_an_index()
    {

        $response = $this->indexManagement->delete();
        $this->assertEquals(['result' => 'ok'], $response);
    }

    /**
     * @test
     */
    public function it_put_settings()
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

        $response = $this->indexManagement->putSettings($settings);
        $this->assertEquals(['result' => 'ok'], $response);
    }

    public function it_get_settings()
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

        $this->indexManagement->putSettings($settings);
        $response = $this->indexManagement->getSettings();

        $this->assertEquals($settings, $response);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_get_settings_index_param_is_not_an_array()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $response = $this->indexManagement->getSettings('hola');
    }

    /**
     * @test
     */
    public function it_put_mapping()
    {
        $type = 'my_type2';
        $mapping = [
            $type => [
                '_source'    => [
                    'enabled' => true
                ],
                'properties' => [
                    'first_name' => [
                        'type'     => 'string',
                        'analyzer' => 'standard'
                    ],
                    'age'        => [
                        'type' => 'integer'
                    ]
                ]
            ]
        ];

        $response = $this->indexManagement->putMapping($mapping, $type);

        $this->assertEquals(['result' => 'ok'], $response);
    }

    /**
     * @test
     */
    public function it_set_the_mappings_params_with_default_index()
    {
        $this->indexManagement->setIndexName($this->indexName);
        $type = 'my_type2';
        $mapping = [
            $type => [
                '_source'    => [
                    'enabled' => true
                ],
                'properties' => [
                    'first_name' => [
                        'type'     => 'string',
                        'analyzer' => 'standard'
                    ],
                    'age'        => [
                        'type' => 'integer'
                    ]
                ]
            ]
        ];
        $mappings = $this->invokeMethod($this->indexManagement, 'configMapping', [$mapping, $type, null]);

        $expected = $mapping = [
            'index' => $this->indexName,
            'type'  => $type,
            'body'  => $mapping
        ];;
        $this->assertEquals($expected, $mappings);
    }

    /**
     * @test
     */
    public function it_set_the_mappings_params_with_index()
    {
        $index = 'index2';
        $type = 'my_type2';
        $mapping = [
            $type => [
                '_source'    => [
                    'enabled' => true
                ],
                'properties' => [
                    'first_name' => [
                        'type'     => 'string',
                        'analyzer' => 'standard'
                    ],
                    'age'        => [
                        'type' => 'integer'
                    ]
                ]
            ]
        ];
        $mappings = $this->invokeMethod($this->indexManagement, 'configMapping', [$mapping, $type, $index]);

        $expected = $mapping = [
            'index' => $index,
            'type'  => $type,
            'body'  => $mapping
        ];
        $this->assertEquals($expected, $mappings);
    }

    /**
     * @test
     */
    public function it_get_mappings()
    {
        $type = 'myIndex';
        $response = $this->indexManagement->getMappings($type);

        $this->assertEquals(['result' => 'ok'], $response);
    }

    /**
     * @test
     */
    public function it_set_the_get_mappings_params_with_index()
    {

        $mappings = $this->invokeMethod($this->indexManagement, 'setGetMappingParams', [null, $this->indexName]);

        $expected = $mapping = [
            'index' => $this->indexName,
        ];
        $this->assertEquals($expected, $mappings);
    }

    /**
     * @test
     */
    public function it_set_the_get_mappings_params_with_type()
    {

        $mappings = $this->invokeMethod($this->indexManagement, 'setGetMappingParams', ['type', null]);

        $expected = $mapping = [
            'type' => 'type',
        ];
        $this->assertEquals($expected, $mappings);
    }

    /**
     * @test
     */
    public function it_set_the_get_mappings_params_with_type_and_index()
    {

        $mappings = $this->invokeMethod($this->indexManagement, 'setGetMappingParams', ['type', 'index2']);

        $expected = $mapping = [
            'index' => 'index2',
            'type'  => 'type',
        ];
        $this->assertEquals($expected, $mappings);
    }

    /**
     * @test
     */
    public function it_set_the_get_mappings_params_with_default_index()
    {
        $this->indexManagement->setIndexName($this->indexName);
        $mappings = $this->invokeMethod($this->indexManagement, 'setGetMappingParams', [null, null]);

        $expected = $mapping = [
            'index' => $this->indexName,
        ];
        $this->assertEquals($expected, $mappings);
    }

    /**
     * @test
     */
    public function it_call_another_method_in_elasticsearch_client()
    {
        $type = 'myIndex';
        $response = $this->indexManagement->analyze($type);
        $this->assertEquals(['result' => 'ok'], $response);
    }

    /**
     * @test
     */
    public function it_recreate_an_index()
    {
        $response = $this->indexManagement->recreate();
        $this->assertEquals(['result' => 'ok'], $response);
    }

}
