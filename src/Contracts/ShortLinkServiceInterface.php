<?php

declare(strict_types=1);

namespace Mohammaddv\Shortener\Contracts;

use Mohammaddv\Shortener\Models\ShortLink;

/**
 * Interface for short link services
 */
interface ShortLinkServiceInterface
{
    /**
     * Create a new short link
     *
     * @param string $url
     * @return ShortLink The created short link
     */
    public function create(string $url): ShortLink;

    /**
     * Resolve a short link
     *
     * @param string $code
     * @return string The original URL
     */
    public function resolve(string $code): string;
}