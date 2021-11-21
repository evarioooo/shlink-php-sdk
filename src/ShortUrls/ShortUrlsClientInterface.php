<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\SDK\ShortUrls;

use Shlinkio\Shlink\SDK\ShortUrls\Model\ShortUrlsList;

interface ShortUrlsClientInterface
{
    public function listShortUrls(): ShortUrlsList;
}
