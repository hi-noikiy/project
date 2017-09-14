<?php

namespace Micro\Frameworks\Filesystem;

/**
 * Exception to be thrown when an unexpected file exists
 */
class UnexpectedFile extends \RuntimeException implements Exception
{
    private $key;

    public function __construct($key, $code = 0, \Exception $previous = null)
    {
        $this->key = $key;

        parent::__construct(
            sprintf('The file "%s" was not supposed to exist.', $key),
            $code,
            $previous
        );
    }

    public function getKey()
    {
        return $this->key;
    }
}
