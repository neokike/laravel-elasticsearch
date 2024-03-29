<?php

namespace Neokike\LaravelElasticSearch\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Neokike\LaravelElasticSearch\Commands\CreateIndexCommand;
use Neokike\LaravelElasticSearch\Commands\DeleteIndexCommand;
use Neokike\LaravelElasticSearch\Commands\GetIndexSettingsCommand;
use Neokike\LaravelElasticSearch\Commands\IndexModelCommand;
use Neokike\LaravelElasticSearch\Commands\ReCreateIndexCommand;
use Neokike\LaravelElasticSearch\Handlers\DocumentIndexer;
use Neokike\LaravelElasticSearch\Handlers\IndexManager;
use Neokike\LaravelElasticSearch\Handlers\Searcher;
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
        $this->app->singleton('Neokike\LaravelElasticSearch\Handlers\DocumentIndexer', function ($app) {
            $config = $app['config']->get('laravelElasticSearch');
            $logger = ClientBuilder::defaultLogger($config['logPath']);

            $elasticSearchClient = ClientBuilder::create()
                ->setLogger($logger)
                ->fromConfig($config['connection']);

            $searchHandler = new DocumentIndexer($elasticSearchClient);
            $searchHandler->setIndexName($config['defaultIndexName']);

            return $searchHandler;
        });
        $this->app->singleton('Neokike\LaravelElasticSearch\Handlers\IndexManager', function ($app) {
            $config = $app['config']->get('laravelElasticSearch');
            $logger = ClientBuilder::defaultLogger($config['logPath']);

            $elasticSearchClient = ClientBuilder::create()
                ->setLogger($logger)
                ->fromConfig($config['connection']);

            $searchHandler = new IndexManager($elasticSearchClient);
            $searchHandler->setIndexName($config['defaultIndexName']);
            $searchHandler->setAnalysis($config['analysis']);
            $searchHandler->setMappings($config['mappings']);
            $searchHandler->setShrads($config['number_of_shards']);
            $searchHandler->setReplicas($config['number_of_replicas']);

            return $searchHandler;
        });

        $this->app->singleton('Neokike\LaravelElasticSearch\Handlers\Searcher', function ($app) {
            $config = $app['config']->get('laravelElasticSearch');
            $logger = ClientBuilder::defaultLogger($config['logPath']);

            $elasticSearchClient = ClientBuilder::create()
                ->setLogger($logger)
                ->fromConfig($config['connection']);

            $searchHandler = new Searcher($elasticSearchClient);
            $searchHandler->setIndexName($config['defaultIndexName']);

            return $searchHandler;
        });
        
        $this->app['command.elasticsearch.createIndex'] = $this->app->share(
            function ($app) {
                $config = $app['config']->get('laravelElasticSearch');
                return new CreateIndexCommand($config);
            }
        );

        $this->app['command.elasticsearch.recreateIndex'] = $this->app->share(
            function ($app) {
                $config = $app['config']->get('laravelElasticSearch');
                return new ReCreateIndexCommand($config);
            }
        );

        $this->app['command.elasticsearch.deleteIndex'] = $this->app->share(
            function ($app) {
                return new DeleteIndexCommand();
            }
        );

        $this->app['command.elasticsearch.getSettings'] = $this->app->share(
            function ($app) {
                return new GetIndexSettingsCommand();
            }
        );

        $this->app['command.elasticsearch.indexModel'] = $this->app->share(
            function ($app) {
                return new IndexModelCommand();
            }
        );

        $this->commands(['command.elasticsearch.createIndex',
                         'command.elasticsearch.deleteIndex',
                         'command.elasticsearch.recreateIndex',
                         'command.elasticsearch.getSettings',
                         'command.elasticsearch.indexModel']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('DocumentIndexer', 'IndexManager');
    }
}