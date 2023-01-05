<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Provider;

use Base\DomainSupport\Domain\Domain;

final class Provider implements Domain
{
    /**
     * @param static $domain
     *
     * @return bool
     */
    public function equals(Domain $domain): bool
    {
        return true;
    }
}
