<?php

namespace Neokike\LaravelElasticSearch\Handlers;

use Elasticsearch\Client;
use Illuminate\Support\Facades\Input;
use Neokike\LaravelElasticSearch\Collections\ElasticSearchCollection;
use Neokike\LaravelElasticSearch\Contracts\ElasticSearchSearchHandlerInterface;
use Neokike\LaravelElasticSearch\Exceptions\InvalidArgumentException;
use Neokike\LaravelElasticSearch\Handlers\Traits\ElasticSearchHandlerTrait;
use Neokike\LaravelElasticsearchQueryBuilder\ElasticQueryBuilder;
use Neokike\LaravelElasticsearchQueryBuilder\Interfaces\QueryInterface;
use Neokike\LaravelElasticsearchQueryBuilder\Queries\Bool\BoolQuery;
use Neokike\LaravelElasticsearchQueryBuilder\Queries\Match\MatchQuery;

class ElasticSearchSearchHandler implements ElasticSearchSearchHandlerInterface
{
    use ElasticSearchHandlerTrait;

    /**
     * @var Client
     */
    protected $elasticsearch;
    /**
     * @var string
     */
    protected $indexName;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
        $this->elasticQueryBuilder = new ElasticQueryBuilder();
        $this->elasticBoolQuery = new BoolQuery();
    }

    public function size($size)
    {
        $this->elasticQueryBuilder->size($size);

        return $this;
    }

    /**
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        $this->elasticQueryBuilder->source($source);

        return $this;
    }

    /**
     * @param $score
     * @return $this
     */
    public function min_score($score)
    {
        $this->elasticQueryBuilder->min_score($score);

        return $this;
    }

    /**
     * @param $from
     * @return $this
     */
    public function from($from)
    {
        $this->elasticQueryBuilder->from($from);

        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->elasticQueryBuilder->type($type);

        return $this;
    }


    public function must($field, $search)
    {
        $match = new MatchQuery($field, $search);
        $this->elasticBoolQuery->setMust($match);
        return $this;
    }

    public function mustNot($field, $search)
    {
        $match = new MatchQuery($field, $search);
        $this->elasticBoolQuery->setMustNot($match);
        return $this;
    }

    public function should($field, $search)
    {
        $match = new MatchQuery($field, $search);
        $this->elasticBoolQuery->setShould($match);
        return $this;
    }

    public function where($field, $search)
    {
        $match = new MatchQuery($field, $search);
        $this->elasticBoolQuery->setFilter($match);
        return $this;
    }

    public function raw($rawQuery)
    {
        $this->elasticQueryBuilder->raw($rawQuery);
        return $this;
    }

    public function elasticQuery($query)
    {
        if (!($query instanceof ElasticQueryBuilder)) {
            throw new InvalidArgumentException;
        }

        $this->elasticQueryBuilder->query($query);
        return $this;

    }

    public function query()
    {
        return $this->elasticQueryBuilder->get();
    }

    /**
     * it performs a search
     *
     * @param $query
     * @return mixed
     */
    public function search($query = null)
    {
        $query = $this->usedQuery($query);

        if (!array_has($query, 'index'))
            $query['index'] = $this->indexName;

        $results = $this->elasticsearch->search($query);

        return new ElasticSearchCollection($results);
    }


    public function paginate($limit = 10, $page = 1, $query = null)
    {
        $query = $this->usedQuery($query);

        if ($this->elasticQueryBuilder->size) {
            $limit = $this->elasticQueryBuilder->size;
        }

        $page = $this->getPage($page);

        $page--;
        $from = $page * $limit;

        $query['body']['from'] = $from;
        $query['body']['size'] = $limit;

        if (!array_has($query, 'index'))
            $query['index'] = $this->indexName;

        $results = $this->elasticsearch->search($query);

        $search = new ElasticSearchCollection($results);

        return $search->paginate($limit);

    }

    /**
     * @param $query
     * @return mixed
     */
    private function usedQuery($query)
    {
        if (!$query) {
            if (!$this->elasticQueryBuilder->search) {
                $query = $this->elasticQueryBuilder->search($this->elasticBoolQuery)->get();
                return $query;
            } else {
                $query = $this->elasticQueryBuilder->get();
                return $query;
            }
        }

        if ($query instanceof QueryInterface)
            $query = $this->elasticQueryBuilder->search($query)->get();

        return $query;
    }

    /**
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
}
