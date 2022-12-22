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
    /**
     * @param DateTime $value
     */
    public function __construct(private DateTime $value)
    {
    }

    /**
     * @param bool   $dateTime
     * @param string $format
     *
     * @return DateTime|string
     */
    public function value(bool $dateTime = false, string $format = DateTimeInterface::ISO8601): DateTime|string
    {
        if ($dateTime) {
            return $this->value;
        }
        return $this->value->format($format);
    }

    /**
     * @param ValueObject $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
        return $valueObject->value() === $this->value;
    }

    /**
     * @param DateTime $value
     *
     * @return static
     */
    public static function of(DateTime $value): static
    {
        return new static($value);
    }
}
