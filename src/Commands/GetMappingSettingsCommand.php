<?php
namespace Neokike\LaravelElasticSearch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;

class GetMappingSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:getMapping {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get the default index mapping';
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
        $type = $this->argument('type');
        $mappings = $this->manager->getMappings($type);
        $this->info(json_encode($mappings));
    }
}
