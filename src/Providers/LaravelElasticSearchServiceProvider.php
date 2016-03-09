<?php

namespace Neokike\LaravelElasticSearch\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexDocumentsHandler;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;
use Neokike\LaravelElasticSearch\Handlers\LaravelElasticSearch;
use Neokike\LaravelElasticsearchQueryBuilder\Providers\LaravelElasticSearchQueryBuilderServiceProvider;

class LaravelElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../resources/config/laravelElasticSearch.php' => $this->app->configPath() . '/' . 'laravelElasticSearch.php',
        ], 'config');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->register(LaravelElasticSearchQueryBuilderServiceProvider::class);
        $this->app->singleton('Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexDocumentsHandler', function ($app) {
            $config = $app['config']->get('laravelElasticSearch');
            $logger = ClientBuilder::defaultLogger($config['logPath']);

            $elasticSearchClient = ClientBuilder::create()
                ->setLogger($logger)
                ->fromConfig($config['connection']);

            $searchHandler = new ElasticSearchIndexDocumentsHandler($elasticSearchClient);
            $searchHandler->setIndexName($config['defaultIndexName']);

            return $searchHandler;
        });
        $this->app->singleton('Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler', function ($app) {
            $config = $app['config']->get('laravelElasticSearch');
            $logger = ClientBuilder::defaultLogger($config['logPath']);

            $elasticSearchClient = ClientBuilder::create()
                ->setLogger($logger)
                ->fromConfig($config['connection']);

            $searchHandler = new ElasticSearchIndexManagementHandler($elasticSearchClient);
            $searchHandler->setIndexName($config['defaultIndexName']);

            $searchHandler->setAnalysis($config['analysis']);
            $searchHandler->setMappings($config['mappings']);
            $searchHandler->setShrads($config['number_of_shards']);
            $searchHandler->setReplicas($config['number_of_replicas']);

            return $searchHandler;
        });

        $this->app->singleton('Neokike\LaravelElasticSearch\Handlers\LaravelElasticSearch', function ($app) {

            $laravelElasticSearch = new LaravelElasticSearch($app['ElasticSearchIndexManagement'], $app['ElasticSearchIndexDocuments']);
            return $laravelElasticSearch;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('ElasticSearchIndexDocuments', 'ElasticSearchIndexManagement', 'LaravelElasticSearch');
    }
}