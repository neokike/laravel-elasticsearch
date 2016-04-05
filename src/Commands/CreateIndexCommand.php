<?php
namespace Neokike\LaravelElasticSearch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;

class CreateIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'configure the elasticsearh server with the mappings provide in the config file';
    /**
     * @var
     */
    private $config;
    /**
     * @var ElasticSearchIndexManagementHandler
     */
    private $manager;

    /**
     * Create a new command instance.
     *
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
        $this->manager = App::make(ElasticSearchIndexManagementHandler::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = $this->manager->create();
        $this->info('index created');
    }
}
