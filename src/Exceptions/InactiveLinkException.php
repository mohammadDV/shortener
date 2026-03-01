<?php

namespace Mohammad\Shortener\Exceptions;

use Exception;

/**
 * Exception for inactive short links
 */
class InactiveLinkException extends Exception
{
    /**
     * Constructor for InactiveLinkException
     *
     * @param string $message The exception message
     * @param int $code The exception code
     */
    public function __construct(
        string $message = 'This short link is inactive.',
        int $code = 410
    ) {
        parent::__construct($message, $code);
    }
}