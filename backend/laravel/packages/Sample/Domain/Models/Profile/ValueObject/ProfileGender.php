<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile\ValueObject;

use Base\DomainSupport\ValueObject\ValueObject;
use Sample\Domain\Models\Profile\Exception\ProfileGenderException;

/**
 *
 */
enum ProfileGender: int implements ValueObject
{
    case Woman = 0;
    case Man = 1;
    case Other = 2;

    /**
     * @param ProfileGender $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value === $valueObject->value;
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * @param int $genderValue
     *
     * @return ProfileGender
     *
     * @throws ProfileGenderException
     */
    public static function of(int $genderValue): ProfileGender
    {
        return match ($genderValue) {
            0 => ProfileGender::Woman,
            1 => ProfileGender::Man,
            2 => ProfileGender::Other,
            default => throw new ProfileGenderException(ProfileGenderException::MESSAGE)
        };
    }
}
