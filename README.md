# Shortener

Production-ready URL shortener package for Laravel. Create short links, resolve them with caching, and redirect visitors to the original URL. Supports expiration, active/inactive links, and click tracking.

## Requirements

- PHP 8.2+
- Laravel 12.x
- Illuminate Database 12.x

## Installation

Install via Composer:

```bash
composer require mohammaddv/shortener
```

The package will auto-register its service provider. Publish the config (optional):

```bash
php artisan vendor:publish --tag=shortener-config
```

Run migrations to create the `short_links` table:

```bash
php artisan migrate
```

## Configuration

Config file `config/shortener.php` (after publishing):

| Option      | Description                    | Default |
|------------|--------------------------------|---------|
| `cache_ttl`| Cache TTL in seconds for lookups | 3600  |
| `rate_limit` | Rate limit (if used in your app) | 10   |

## Usage

### Creating a short link

Inject `ShortLinkServiceInterface` and call `create()` with the full URL:

```php
use Mohammaddv\Shortener\Contracts\ShortLinkServiceInterface;
use Mohammaddv\Shortener\Models\ShortLink;

class YourController extends Controller
{
    public function store(Request $request, ShortLinkServiceInterface $shortener): ShortLink
    {
        $link = $shortener->create($request->input('url'));

        // $link->code     - short code (e.g. "a1B2")
        // $link->original_url
        // $link->expires_at (nullable)
        // $link->is_active

        return $link;
    }
}
```

The service:

- Validates the URL (format, scheme, reachable, no internal IPs).
- Creates a `ShortLink` and assigns a Base62 `code` from the record ID.
- Returns the `ShortLink` model.

Invalid or unreachable URLs throw `Mohammaddv\Shortener\Exceptions\InvalidUrlException`.

### Redirecting by short code

The package registers a catch-all route so that visiting `/{code}` resolves the short link and redirects to the original URL.

- Resolved URLs are cached (TTL from `config/shortener.cache_ttl`).
- Clicks are incremented asynchronously.
- Inactive links throw `InactiveLinkException` (410).
- Expired links (`expires_at` in the past) throw `ExpiredLinkException` (410).

Ensure this route does not conflict with your other routes (e.g. mount it under a prefix or subdomain in your app).

### Resolving a code in code

To get the original URL without redirecting:

```php
use Mohammaddv\Shortener\Contracts\ShortLinkServiceInterface;

$url = app(ShortLinkServiceInterface::class)->resolve('a1B2');
// Returns the original URL string; throws if not found, inactive, or expired
```

### ShortLink model

- **Fillable:** `original_url`, `code`, `expires_at`
- **Casts:** `expires_at` → `datetime`, `is_active` → `boolean`
- **Default:** `is_active` is `true`, `clicks` is `0`

Set `expires_at` and `is_active` when creating or updating links to control validity.

## Exceptions

| Exception | When | HTTP code |
|-----------|------|-----------|
| `InvalidUrlException` | Invalid or unreachable URL on create | 400 |
| `InactiveLinkException` | Link has `is_active = false` on resolve | 410 |
| `ExpiredLinkException` | Link has passed `expires_at` on resolve | 410 |

Handle these in your Laravel exception handler or controller as needed.

## License

MIT
