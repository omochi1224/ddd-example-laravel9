<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Profile\ValueObject;


use Base\DomainSupport\ValueObject\ValueObject;

/**
 *
 */
enum ProfileGender: int implements ValueObject
{
    case Men = 0;
    case Man = 1;
    case Other = 2;

    /**
     * @param ProfileGender $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value == $valueObject->value;
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }
}
