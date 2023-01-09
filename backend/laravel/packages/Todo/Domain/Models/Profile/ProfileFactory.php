<?php

declare(strict_types=1);

namespace Todo\Domain\Models\Profile;

use Base\DomainSupport\Exception\InvalidUuidException;
use Base\FactorySupport\Factory;
use Todo\Domain\Models\Profile\Exception\ProfileGenderException;
use Todo\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;
use Todo\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Todo\Domain\Models\Profile\ValueObject\ProfileGender;
use Todo\Domain\Models\Profile\ValueObject\ProfileId;
use Todo\Domain\Models\Profile\ValueObject\ProfileImage;
use Todo\Domain\Models\Profile\ValueObject\ProfileName;

final readonly class ProfileFactory implements Factory
{
    /**
     * @throws InvalidUuidException
     * @throws ProfileGenderException
     * @throws ProfileInvalidImageUrlException
     */
    public static function definitive(
        object $input
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
     * @throws InvalidUuidException
     * @throws ProfileInvalidImageUrlException|ProfileGenderException
     */
    public static function makeFromRecord(object $ormObject): Profile
    {
        return Profile::restoreFromDB(
            ProfileId::of($ormObject->profile_id),
            ProfileName::of($ormObject->firstName->value(), $ormObject->lastName->value()),
            ProfileBirthDay::of($ormObject->profile_birthday),
            ProfileGender::of($ormObject->gender),
            ProfileImage::of($ormObject->profile_image)
        );
    }
}
