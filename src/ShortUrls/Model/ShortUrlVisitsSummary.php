<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\SDK\ShortUrls\Model;

final class ShortUrlVisitsSummary
{
    private function __construct(
        public readonly int $total,
        public readonly ?int $nonBots,
        public readonly ?int $bots,
    ) {
    }

    public static function fromArrayWithFallback(array $payload, int $fallbackTotal): self
    {
        return new self(
            $payload['total'] ?? $fallbackTotal,
            $payload['nonBots'] ?? null,
            $payload['bots'] ?? null,
        );
    }
}
