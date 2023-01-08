<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

use DateTime;
use DateTimeInterface;

/**
 *
 */

readonly abstract class DateTimeValueObject implements ValueObject
{
    public function __construct(private DateTime $value)
    {
    }

    public function value(bool $dateTime = false, string $format = DateTimeInterface::ISO8601): DateTime|string
    {
        if ($dateTime) {
            return $this->value;
        }
        return $this->value->format($format);
    }

    public function equals(ValueObject $valueObject): bool
    {
        return $valueObject->value() === $this->value;
    }

    public static function of(DateTime $value): static
    {
        return new static($value);
    }
}
