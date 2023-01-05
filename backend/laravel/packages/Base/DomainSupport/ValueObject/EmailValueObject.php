<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

use Base\DomainSupport\Exception\InvalidEmailAddressException;

readonly abstract class EmailValueObject extends StringValueObject
{
    /**
     * @throws InvalidEmailAddressException
     */
    public function __construct(string $value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException(InvalidEmailAddressException::MESSAGE);
        }
        parent::__construct($value);
    }
}
