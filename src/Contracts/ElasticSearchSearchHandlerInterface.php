<?php
namespace Neokike\LaravelElasticSearch\Contracts;

use Elasticsearch\Client;

interface ElasticSearchSearchHandlerInterface
{

    /**
     * Get the underlying client.
     *
     * @return Client
     */
    public function getClient();

    /**
     * Set the name of the index that should be used by default.
     *
     * @param $indexName
     *
     * @return $this
     */
    public function setIndexName($indexName);

    /**
     * it performs a search
     *
     * @param $query
     * @return mixed
     */
    public function search($query);

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

    public function must($field, $search);

    public function mustNot($field, $search);

    public function should($field, $search);

    public function where($field, $search);

    public function raw($rawQuery);

    public function elasticQuery($query);

    public function query();


}
