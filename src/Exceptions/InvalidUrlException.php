<?php

namespace Mohammad\Shortener\Exceptions;

use Exception;

/**
 * Exception for invalid URLs
 */
class InvalidUrlException extends Exception
{
    /**
     * Constructor for InvalidUrlException
     *
     * @param string $message The exception message
     * @param int $code The exception code
     */
    public function __construct(
        string $message = 'The provided URL is invalid.',
        int $code = 400
    ) {
        parent::__construct($message, $code);
    }
}