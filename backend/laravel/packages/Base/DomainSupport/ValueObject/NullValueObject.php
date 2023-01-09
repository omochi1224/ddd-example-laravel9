<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

/**
 *
 */
abstract readonly class NullValueObject implements ValueObject
{
    public static function of(): NullValueObject
    {
        return new static();
    }

    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    public function value(): null
    {
        return null;
    }
}
