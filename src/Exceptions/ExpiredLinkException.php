<?php

namespace Mohammaddv\Shortener\Exceptions;

use Exception;

/**
 * Exception for expired short links
 */
class ExpiredLinkException extends Exception
{
    /**
     * Constructor for ExpiredLinkException
     *
     * @param string $message The exception message
     * @param int $code The exception code
     */
    public function __construct(
        string $message = 'This short link has expired.',
        int $code = 410
    ) {
        parent::__construct($message, $code);
    }
}