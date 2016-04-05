<?php
namespace Neokike\LaravelElasticSearch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Neokike\LaravelElasticSearch\Handlers\ElasticSearchIndexManagementHandler;

class GetIndexSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:getSettings {index?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get the default index settings';
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
        $indexes = [];
        $index = $this->argument('index');
        if ($index) {
            $indexes[] = $index;
        }
        $this->info($this->manager->getSettings($indexes));
    }
}
