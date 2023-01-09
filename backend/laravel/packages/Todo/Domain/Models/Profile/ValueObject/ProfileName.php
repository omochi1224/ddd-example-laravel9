<?php

declare(strict_types=1);

namespace Todo\Domain\Models\Profile\ValueObject;

use Base\DomainSupport\ValueObject\ValueObject;

/**
 *
 */
final readonly class ProfileName implements ValueObject
{
    public function __construct(
        private ProfileLastName $lastName,
        private ProfileFirstName $firstName,
    ) {
    }

    public static function of(string $lastName, string $firstName): ProfileName
    {
        return new ProfileName(
            ProfileLastName::of($lastName),
            ProfileFirstName::of($firstName),
        );
    }

    public function equals(self|ValueObject $valueObject): bool
    {
        if (! ($valueObject instanceof ProfileName)) {
            throw new \TypeError();
        }

        $lastName = $valueObject->lastName;
        $firstName = $valueObject->firstName;

        return $this->lastName->equals($lastName)
            && $this->firstName->equals($firstName);
    }

    /**
     * @return array{lastName: string, firstName: string}
     */
    public function value(): array
    {
        return [
            'lastName' => $this->lastName->value(),
            'firstName' => $this->firstName->value(),
        ];
    }
}
