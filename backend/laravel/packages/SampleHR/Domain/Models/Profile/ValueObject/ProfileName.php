<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Profile\ValueObject;


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
     * @return ProfileName
     */
    public static function temporaryRegister(): ProfileName
    {
        return new ProfileName(
            ProfileLastName::of(''),
            ProfileFirstName::of(''),
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
