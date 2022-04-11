<?php

declare(strict_types=1);

namespace Base\DomainSupport\Domain;

/**
 * ドメインと識別するためのmarker interface
 */
interface Domain
{
    /**
     * @param Domain $domain
     *
     * @return bool
     */
    public function equals(self $domain): bool;
}
