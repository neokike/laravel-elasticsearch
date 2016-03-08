<?php
namespace Neokike\LaravelElasticSearch\Contracts;

interface ElasticSearchRepositoryInterface
{

    /**
     * set size
     * @param $size
     * @return mixed
     */
    public function size($size);

    /**
     * @param $source
     * @return $this
     */
    public function source($source);

    /**
     * @param $score
     * @return $this
     */
    public function min_score($score);

    /**
     * @param $from
     * @return $this
     */
    public function from($from);

    /**
     * @param $type
     * @return $this
     */
    public function type($type);


    /**
     * @param $field
     * @param $search
     * @return mixed
     */
    public function must($field, $search);

    /**
     * @param $field
     * @param $search
     * @return mixed
     */
    public function mustNot($field, $search);

    /**
     * @param $field
     * @param $search
     * @return mixed
     */
    public function should($field, $search);

    /**
     * @param $field
     * @param $search
     * @return mixed
     */
    public function where($field, $search);

    /**
     * @param $rawQuery
     * @return mixed
     */
    public function raw($rawQuery);

    /**
     * @param $query
     * @return mixed
     */
    public function elasticQuery($query);

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @return mixed
     */
    public function build();
}