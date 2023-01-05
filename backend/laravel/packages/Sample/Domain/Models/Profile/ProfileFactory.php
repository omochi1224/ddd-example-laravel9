<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile;

use Base\DomainSupport\Exception\InvalidUuidException;
use Base\FactorySupport\Factory;
use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserInput;
use Sample\Domain\Models\Profile\Exception\ProfileGenderException;
use Sample\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;
use Sample\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Sample\Domain\Models\Profile\ValueObject\ProfileGender;
use Sample\Domain\Models\Profile\ValueObject\ProfileId;
use Sample\Domain\Models\Profile\ValueObject\ProfileImage;
use Sample\Domain\Models\Profile\ValueObject\ProfileName;

final readonly class ProfileFactory implements Factory
{
    /**
     * @param DefinitiveRegisterUserInput $input
     *
     * @return IProfile
     *
     * @throws InvalidUuidException
     * @throws ProfileGenderException
     * @throws ProfileInvalidImageUrlException
     */
    public static function definitive(
        DefinitiveRegisterUserInput $input
    ): IProfile {
        return Profile::definitive(
            ProfileName::of(
                $input->getName()['lastName'],
                $input->getName()['firstName']
            ),
            ProfileBirthDay::of($input->getBirthday()),
            ProfileGender::of($input->getGender()),
            ProfileImage::of($input->getImage()),
        );
    }

    /**
     * 永続化からの復帰
     *
     * @param object $ormObject
     *
     * @return Profile
     *
     * @throws InvalidUuidException
     * @throws ProfileInvalidImageUrlException|ProfileGenderException
     */
    public static function makeFromRecord(object $ormObject): Profile
    {
        return Profile::restoreFromDB(
            ProfileId::of($ormObject->profile_id),
            ProfileName::of($ormObject->firstName->value(), $ormObject->lastName->value()),
            ProfileBirthday::of($ormObject->profile_birthday),
            ProfileGender::of($ormObject->gender),
            ProfileImage::of($ormObject->profile_image)
        );
    }
}
