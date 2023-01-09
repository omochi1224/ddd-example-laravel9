<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

use Base\DomainSupport\ValueObject\ValueObject;

abstract readonly class Password implements ValueObject
{
    public function __construct(protected ?string $value)
    {
    }

    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }
}
