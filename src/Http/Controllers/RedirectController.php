<?php

declare(strict_types=1);

namespace Mohammad\Shortener\Http\Controllers;

use Illuminate\Routing\Controller;
use Mohammad\Shortener\Contracts\ShortLinkServiceInterface;

/**
 * Controller for redirecting to the original URL
 */
class RedirectController extends Controller
{
    /**
     * Invoke the redirect controller
     *
     * @param string $code
     * @param ShortLinkServiceInterface $service
     * @return mixed
     */
    public function __invoke(
        string $code,
        ShortLinkServiceInterface $service
    ): mixed
    {
        $url = $service->resolve($code);

        return redirect()->away($url);
    }
}