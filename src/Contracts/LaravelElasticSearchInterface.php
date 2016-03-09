<?php
namespace Neokike\LaravelElasticSearch\Contracts;

interface LaravelElasticSearchInterface
{

    /**
     * index an Searchable object or a collection of them
     * @param $documents
     * @return mixed
     */
    public function indexDocuments($documents);


    /**
     * delete an Searchable object or a collection of them
     * @param $documents
     * @return mixed
     */
    public function deleteDocuments($documents);


    /**
     * Perform a search
     * @param $query
     * @return mixed
     */
    public function search($query);


}