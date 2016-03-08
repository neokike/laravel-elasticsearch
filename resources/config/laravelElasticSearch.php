<?php

return [
    /*
     * Specify the host(s) where elasticsearch is running.
     */
    'connection'         => [
        'hosts'   => [
            'localhost:9200'
        ],
        'retries' => 2,
    ],
    /*
     * Specify the path where Elasticsearch will write it's logs.
     */
    'logPath'            => storage_path() . '/logs/elasticsearch.log',
    /*
     * Specify how verbose the logging must be
     * Possible values are listed here
     * https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
     *
     */
    'logLevel'           => 200,
    /*
     * The name of the index elasticsearch will write to.
     */
    'defaultIndexName'   => 'main',

    'analysis'           => [],

    'mappings'           => [],

    'number_of_shards'   => 1,

    'number_of_replicas' => 0
];