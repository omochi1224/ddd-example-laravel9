<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

/**
 *
 */
abstract readonly class NullValueObject implements ValueObject
{
    /**
     * @return NullValueObject
     */
    public static function of(): NullValueObject
    {
        return new static();
    }

    /**
     * @param ValueObject $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    /**
     * @return mixed
     */
    public function value(): null
    {
        return null;
    }
}
