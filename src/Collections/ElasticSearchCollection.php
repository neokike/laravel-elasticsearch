<?php
namespace Neokike\LaravelElasticSearch\Collections;

use Neokike\LaravelElasticSearch\Paginators\ElasticSearchPaginator as Paginator;

class ElasticSearchCollection extends \Illuminate\Database\Eloquent\Collection
{
    protected $took;
    protected $timed_out;
    protected $shards;
    protected $hits;
    protected $aggregations = null;
    protected $instance;

    /**
     * Create a new instance containing Elasticsearch results
     *
     * @param $results elasticsearch results
     */

    public function __construct($results)
    {
        // Take our result data and map it
        // to some class properties.
        $this->took = $results['took'];
        $this->timed_out = $results['timed_out'];
        $this->shards = $results['_shards'];
        $this->hits = $results['hits'];
        $this->aggregations = isset($results['aggregations']) ? $results['aggregations'] : array();

        $this->items = $this->hitsToItems();

    }

    /**
     * Total Hits
     *
     * @return int
     */
    public function totalHits()
    {
        return $this->hits['total'];
    }

    /**
     * Max Score
     *
     * @return float
     */
    public function maxScore()
    {
        return $this->hits['max_score'];
    }

    /**
     * Get Shards
     *
     * @return array
     */
    public function getShards()
    {
        return $this->shards;
    }

    /**
     * Took
     *
     * @return string
     */
    public function took()
    {
        return $this->took;
    }

    /**
     * Timed Out
     *
     * @return bool
     */
    public function timedOut()
    {
        return (bool)$this->timed_out;
    }

    /**
     * Get Hits
     *
     * Get the raw hits array from
     * Elasticsearch results.
     *
     * @return array
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Get aggregations
     *
     * Get the raw hits array from
     * Elasticsearch results.
     *
     * @return array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Paginate Collection
     *
     * @param int $pageLimit
     *
     * @return Paginator
     */
    public function paginate($pageLimit = 25)
    {
        $page = Paginator::resolveCurrentPage() ?: 1;

        return new Paginator($this->items, $this->hits, $this->totalHits(), $pageLimit, $page, ['path' => Paginator::resolveCurrentPath()]);
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param  int $size
     * @param  bool $preserveKeys
     * @return static
     */
    public function chunk($size, $preserveKeys = false)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, $preserveKeys) as $chunk) {
            $chunks[] = new static($chunk, $this->instance);
        }

        return new static($chunks, $this->instance);
    }

    private function hitsToItems()
    {
        $items = [];

        foreach ($this->hits['hits'] as $hit) {
            $items[] = $this->newObject($hit);
        }

        return $items;
    }

    private function newObject($hit)
    {
        $instance = new \stdClass();
        $instance->_source = $hit['_source'];
        // Add fields to attributes
        if (isset($hit['fields'])) {
            foreach ($hit['fields'] as $key => $value) {
                $instance->$key = $value;
            }
        }

        // In addition to setting the attributes
        // from the index, we will set the score as well.
        $instance->documentScore = $hit['_score'];

        // Set our document version if it's
        if (isset($hit['_version'])) {
            $instance->documentVersion = $hit['_version'];
        }
        return $instance;
    }
}