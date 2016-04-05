<?php
namespace Neokike\LaravelElasticSearch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;

class DeleteIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:delete {index?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the elasticsearh index provided in the config file';
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
    public function __construct()
    {
        parent::__construct();
        $this->manager = App::make(ElasticSearchIndexManagementHandler::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $index = $this->argument('index');
        $this->manager->delete($index);
        $this->info('index deleted');

    }
}
