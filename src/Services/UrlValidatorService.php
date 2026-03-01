<?php

namespace Mohammaddv\Shortener\Services;

use Illuminate\Support\Facades\Http;
use Mohammaddv\Shortener\Contracts\UrlValidatorInterface;
use Mohammaddv\Shortener\Exceptions\InvalidUrlException;

/**
 * Service for validating URLs
 */
class UrlValidatorService implements UrlValidatorInterface
{
    /**
     * Validate a URL
     *
     * @param string $url
     * @return void
     */
    public function validate(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid URL");
        }

        $parsed = parse_url($url);

        if (!in_array($parsed['scheme'], ['http', 'https'])) {
            throw new InvalidUrlException("Invalid scheme");
        }

        $this->preventInternalIp($parsed['host']);

        $response = Http::timeout(5)
            ->withOptions(['allow_redirects' => true])
            ->head($url);

        if ($response->failed()) {
            throw new InvalidUrlException("URL is not reachable");
        }
    }

    /**
     * Prevent internal IP addresses
     *
     * @param string $host
     * @return void
     */
    protected function preventInternalIp(string $host): void
    {
        $ip = gethostbyname($host);

        if (
            str_starts_with($ip, '127.') ||
            str_starts_with($ip, '10.') ||
            str_starts_with($ip, '192.168.')
        ) {
            throw new InvalidUrlException("Internal IP not allowed");
        }
    }
}