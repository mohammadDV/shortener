<?php

namespace Mohammaddv\Shortener\Services;

use Illuminate\Support\Facades\Cache;
use Mohammaddv\Shortener\Models\ShortLink;
use Mohammaddv\Shortener\Support\Base62;
use Mohammaddv\Shortener\Contracts\ShortLinkServiceInterface;
use Mohammaddv\Shortener\Contracts\UrlValidatorInterface;
use Mohammaddv\Shortener\Exceptions\InactiveLinkException;
use Mohammaddv\Shortener\Exceptions\ExpiredLinkException;

/**
 * Service for short links
 */
class ShortLinkService implements ShortLinkServiceInterface
{
    public function __construct(
        protected UrlValidatorInterface $validator
    ) {}

    /**
     * Create a new short link
     *
     * @param string $url
     * @return ShortLink
     */
    public function create(string $url): ShortLink
    {
        $this->validator->validate($url);

        $link = ShortLink::create([
            'original_url' => $url,
        ]);

        $link->code = Base62::encode($link->id);
        $link->save();

        return $link;
    }

    public function resolve(string $code): string
    {
        return Cache::remember("short:$code", 3600, function () use ($code) {

            $link = ShortLink::where('code', $code)->firstOrFail();

            if (!$link->is_active) {
                throw new InactiveLinkException();
            }

            if ($link->expires_at && $link->expires_at->isPast()) {
                throw new ExpiredLinkException();
            }

            dispatch(fn() =>
                ShortLink::where('id', $link->id)->increment('clicks')
            );

            return $link->original_url;
        });
    }
}