<?php
namespace Neokike\LaravelElasticSearch\Exceptions;

use Exception;

class InvalidArgumentException extends Exception
{

    public function __construct($message = '', $code = 0, Exception $previous = null)
    {

        parent::__construct($message, $code, $previous);
    }

}