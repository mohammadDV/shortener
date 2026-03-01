<?php

declare(strict_types=1);

namespace Mohammaddv\Shortener\Contracts;

/**
 * Interface for URL validation services
 */
interface UrlValidatorInterface
{
    /**
     * Validate a URL
     *
     * @param string $url
     * @return void
     */
    public function validate(string $url): void;
}