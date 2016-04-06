<?php
namespace Neokike\LaravelElasticSearch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Neokike\LaravelElasticSearch\Handlers\DocumentIndexer;
use Neokike\LaravelElasticSearch\Handlers\IndexManager;

class IndexModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:indexModel {model : model to index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'index a model in elasticsearch';
    protected $document;
    /**
     * @var
     */
    private $config;
    /**
     * @var IndexManager
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
        $this->manager = App::make(IndexManager::class);
        $this->document = App::make(DocumentIndexer::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->argument('model');

        if (class_exists($model)) {
            $rows = $model::all();
            $this->info('Indexing ' . count($rows) . ' documents (' . $model . ')');
            $bar = $this->output->createProgressBar(count($rows));
            $rows->each(function ($row) use ($model, $bar) {
                $row = $row->prepareModelToIndex();
                $this->document->upsertToIndex($row);
                $bar->advance();
            });
            $bar->finish();
        } else {
            $this->error($model . ' class doesnt exists');
        };
    }
}
