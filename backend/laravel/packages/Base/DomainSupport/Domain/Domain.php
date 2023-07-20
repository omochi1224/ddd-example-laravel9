<?php

declare(strict_types=1);

namespace Base\DomainSupport\Domain;

interface Domain
{
    public function equals(Domain $domain): bool;
}
