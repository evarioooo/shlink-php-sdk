<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\SDK\ShortUrls\Model;

final class ShortUrlIdentifier
{
    private function __construct(public readonly string $shortCode, public readonly ?string $domain)
    {
    }

    public static function fromShortCode(string $shortCode): self
    {
        return new self($shortCode, null);
    }

    public static function fromShortCodeAndDomain(string $shortCode, string $domain): self
    {
        return new self($shortCode, $domain);
    }
}
