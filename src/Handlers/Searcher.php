<?php

namespace Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use Illuminate\Support\Facades\Input;
use Neokike\LaravelElasticSearch\Collections\ElasticSearchCollection;
use Neokike\LaravelElasticSearch\Contracts\SearcherContract;
use Neokike\LaravelElasticSearch\Exceptions\InvalidArgumentException;
use Neokike\LaravelElasticSearch\Handlers\Traits\HandlerTrait;
use Neokike\LaravelElasticsearchQueryBuilder\ElasticQueryBuilder;
use Neokike\LaravelElasticsearchQueryBuilder\Interfaces\QueryInterface;

class Searcher implements SearcherContract
{
    use HandlerTrait;

    /**
     * @var Client
     */
    protected $elasticsearch;
    /**
     * @var string
     */
    protected $indexName;

    protected $query;
    protected $size;
    protected $source;
    protected $min_score;
    protected $from;
    protected $type;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param $score
     * @return $this
     */
    public function min_score($score)
    {
        $this->min_score = $score;

        return $this;
    }

    /**
     * @param $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * it performs a search
     *
     * @return mixed
     */
    public function search()
    {
        $query = $this->usedQuery();

        $query = $this->configQuery($query);

        $results = $this->elasticsearch->search($query);

        return new ElasticSearchCollection($results);
    }


    /**
     * @param int $limit
     * @param int $page
     * @return \Neokike\LaravelElasticSearch\Paginators\ElasticSearchPaginator
     * @throws InvalidArgumentException
     */
    public function paginate($limit = 10, $page = 1)
    {
        $query = $this->usedQuery();
        $query = $this->configQuery($query, true);

        if ($this->size) {
            $limit = $this->size;
        }

        $page = $this->getPage($page);

        $page--;
        $from = $page * $limit;

        $query['body']['from'] = $from;
        $query['body']['size'] = $limit;

        $results = $this->elasticsearch->search($query);

        $search = new ElasticSearchCollection($results);

        return $search->paginate($limit);

    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function usedQuery()
    {
        if ($this->query instanceof QueryInterface){
            $builder = new ElasticQueryBuilder();
            return $builder->query($this->query)->get();

        }

        if ($this->query instanceof ElasticQueryBuilder)
            return $this->query->get();

        if (is_array($this->query))
            return $this->query;

        throw new InvalidArgumentException('the query is not defined');
    }

    /**
     * @param $page
     * @return mixed
     */
    private function getPage($page)
    {
        $urlPage = Input::get('page');

        if ($urlPage) {
            $page = $urlPage;
            return $page;
        }

        return $page;
    }

    /**
     * @param $query
     * @param bool $paginated
     * @return mixed
     */
    private function configQuery($query, $paginated = false)
    {
        if (!array_has($query, 'index'))
            $query['index'] = $this->indexName;

        if (!array_has($query, 'from') && $this->from && !$paginated)
            $query['body']['from'] = $this->from;

        if (!array_has($query, '_source') && $this->source)
            $query['body']['_source'] = $this->source;

        if (!array_has($query, 'size') && $this->size && !$paginated)
            $query['body']['size'] = $this->size;

        if (!array_has($query, 'min_score') && $this->min_score)
            $query['body']['min_score'] = $this->min_score;

        if (!array_has($query, 'type') && $this->type)
            $query['type'] = $this->type;

        return $query;
    }
}
