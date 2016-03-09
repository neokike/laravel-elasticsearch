<?php
namespace Neokike\LaravelElasticSearch\Repositories;

use Neokike\LaravelElasticSearch\Handlers\LaravelElasticSearch;
use Neokike\LaravelElasticsearchQueryBuilder\ElasticQueryBuilder;
use Neokike\LaravelElasticsearchQueryBuilder\Exceptions\InvalidArgumentException;
use Neokike\LaravelElasticsearchQueryBuilder\Interfaces\QueryInterface;
use Neokike\LaravelElasticsearchQueryBuilder\Queries\Bool\ElasticBoolQuery;
use Neokike\LaravelElasticsearchQueryBuilder\Queries\Match\ElasticMatchQuery;

class ElasticSearchBaseRepository
{
    /**
     * @var ElasticQueryBuilder
     */
    private $elasticQueryBuilder;
    /**
     * @var ElasticBoolQuery
     */
    private $elasticBoolQuery;
    /**
     * @var ElasticSearchHandler
     */
    private $elasticSearchHandler;

    public function __construct(ElasticQueryBuilder $elasticQueryBuilder,
                                ElasticBoolQuery $elasticBoolQuery,
                                LaravelElasticSearch $elasticSearchHandler)
    {
        $this->elasticQueryBuilder = $elasticQueryBuilder;
        $this->elasticBoolQuery = $elasticBoolQuery;
        $this->elasticSearchHandler = $elasticSearchHandler;
    }

    /**
     * @param $size
     * @return $this
     */
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
        $match = new ElasticMatchQuery($field, $search);
        $this->elasticBoolQuery->setMust($match);
        return $this;
    }

    public function mustNot($field, $search)
    {
        $match = new ElasticMatchQuery($field, $search);
        $this->elasticBoolQuery->setMustNot($match);
        return $this;
    }

    public function should($field, $search)
    {
        $match = new ElasticMatchQuery($field, $search);
        $this->elasticBoolQuery->setShould($match);
        return $this;
    }

    public function where($field, $search)
    {
        $match = new ElasticMatchQuery($field, $search);
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
        if (!($query instanceof QueryInterface)) {
            throw new InvalidArgumentException;
        }

        $this->elasticSearchHandler->search($query->toArray());
        return $this;
    }

    public function execute()
    {
        if (!$this->elasticQueryBuilder->raw && !$this->elasticQueryBuilder->search)
            $this->elasticSearchHandler->search($this->elasticBoolQuery->toArray());

        return $this->elasticSearchHandler->search($this->query());
    }

    public function query()
    {
        return $this->elasticQueryBuilder->get();
    }
}