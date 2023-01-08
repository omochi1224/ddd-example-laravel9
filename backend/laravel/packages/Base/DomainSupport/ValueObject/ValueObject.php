<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

/**
 * ValueObject根底Interface
 */
interface ValueObject
{
    /**
     * @param ValueObject $valueObject
     *
     * @return bool
     */
    public function equals(self $valueObject): bool;

    /**
     * @return mixed
     */
    public function value(): mixed;
}
