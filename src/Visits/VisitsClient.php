<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\SDK\Visits;

use Closure;
use Shlinkio\Shlink\SDK\Http\HttpClientInterface;
use Shlinkio\Shlink\SDK\ShortUrls\Model\ShortUrlIdentifier;
use Shlinkio\Shlink\SDK\Visits\Model\VisitsFilter;
use Shlinkio\Shlink\SDK\Visits\Model\VisitsList;
use Shlinkio\Shlink\SDK\Visits\Model\VisitsSummary;

use function sprintf;

class VisitsClient implements VisitsClientInterface
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function getVisitsSummary(): VisitsSummary
    {
        return VisitsSummary::fromArray($this->httpClient->getFromShlink('/visits')['visits'] ?? []);
    }

    public function listShortUrlVisits(ShortUrlIdentifier $shortUrlIdentifier): VisitsList
    {
        return $this->listShortUrlVisitsWithFilter($shortUrlIdentifier, VisitsFilter::create());
    }

    public function listShortUrlVisitsWithFilter(
        ShortUrlIdentifier $shortUrlIdentifier,
        VisitsFilter $filter,
    ): VisitsList {
        $shortCode = $shortUrlIdentifier->shortCode();
        $domain = $shortUrlIdentifier->domain();
        $query = $filter->toArray();

        if ($domain !== null) {
            $query['domain'] = $domain;
        }

        return VisitsList::forTupleLoader(
            $this->createVisitsLoaderForUrl(sprintf('/short-urls/%s/visits', $shortCode), $query),
        );
    }

    public function listTagVisits(string $tag): VisitsList
    {
        return $this->listTagVisitsWithFilter($tag, VisitsFilter::create());
    }

    public function listTagVisitsWithFilter(string $tag, VisitsFilter $filter): VisitsList
    {
        return VisitsList::forTupleLoader(
            $this->createVisitsLoaderForUrl(sprintf('/tags/%s/visits', $tag), $filter->toArray()),
        );
    }

    public function listOrphanVisits(): VisitsList
    {
        return $this->listOrphanVisitsWithFilter(VisitsFilter::create());
    }

    public function listOrphanVisitsWithFilter(VisitsFilter $filter): VisitsList
    {
        return VisitsList::forTupleLoader($this->createVisitsLoaderForUrl('/visits/orphan', $filter->toArray()));
    }

    private function createVisitsLoaderForUrl(string $url, array $query): Closure
    {
        return function (int $page) use ($url, $query): array {
            $query['page'] = $page;
            $query['itemsPerPage'] = VisitsList::ITEMS_PER_PAGE;
            $body = $this->httpClient->getFromShlink($url, $query);

            return [$body['visits']['data'] ?? [], $body['visits']['pagination'] ?? []];
        };
    }
}
