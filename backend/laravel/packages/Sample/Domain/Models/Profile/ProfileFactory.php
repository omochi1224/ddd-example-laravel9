<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile;

use Base\FactorySupport\Factory;
use Sample\Domain\Models\Profile\Exception\ProfileGenderException;
use Sample\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;
use Sample\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Sample\Domain\Models\Profile\ValueObject\ProfileGender;
use Sample\Domain\Models\Profile\ValueObject\ProfileId;
use Sample\Domain\Models\Profile\ValueObject\ProfileImage;
use Sample\Domain\Models\Profile\ValueObject\ProfileName;

final readonly class ProfileFactory implements Factory
{
    public static function definitive(
        ProfileName $name,
        ProfileBirthDay $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): IProfile {
        return Profile::definitive($name, $birthDay, $gender, $image);
    }

    public static function makeFromRecord(object $ormObject): Profile
    {
        return Profile::restoreFromDb(
            ProfileId::of($ormObject->profile_id),
            ProfileName::of($ormObject->firstName->value(), $ormObject->lastName->value()),
            ProfileBirthDay::of($ormObject->profile_birthday),
            ProfileGender::of($ormObject->gender),
            ProfileImage::of($ormObject->profile_image)
        );
    }
}
