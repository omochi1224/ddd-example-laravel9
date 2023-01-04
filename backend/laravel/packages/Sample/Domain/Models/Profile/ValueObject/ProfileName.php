<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile\ValueObject;

use Base\DomainSupport\ValueObject\ValueObject;

/**
 *
 */
final readonly class ProfileName implements ValueObject
{
    /**
     * @param ProfileLastName  $lastName
     * @param ProfileFirstName $firstName
     */
    public function __construct(
        private ProfileLastName $lastName,
        private ProfileFirstName $firstName,
    ) {
    }

    /**
     * @param string $lastName
     * @param string $firstName
     *
     * @return ProfileName
     */
    public static function of(string $lastName, string $firstName): ProfileName
    {
        return new ProfileName(
            ProfileLastName::of($lastName),
            ProfileFirstName::of($firstName),
        );
    }

    /**
     * @param ProfileName $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
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
