<?php
namespace Neokike\LaravelElasticSearch\Contracts;

use Elasticsearch\Client;

interface SearcherContract
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
     * @return mixed
     */
    public function search();

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
    
    
    public function query($query);


}
