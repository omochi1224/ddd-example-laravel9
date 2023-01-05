<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile\ValueObject;

use Base\DomainSupport\ValueObject\DateTimeValueObject;
use DateTime;
use Sample\Domain\Models\Profile\Exception\ProfileBirthdayException;

readonly final class ProfileBirthDay extends DateTimeValueObject
{
    /**
     * @throws ProfileBirthdayException
     */
    public function __construct(DateTime $value)
    {
        $now = new DateTime();
        if ($now <= $value) {
            throw new ProfileBirthdayException(ProfileBirthdayException::MESSAGE);
        }

        parent::__construct($value);
    }
}
