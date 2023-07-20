<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile;

use Base\DomainSupport\Domain\Domain;
use Sample\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Sample\Domain\Models\Profile\ValueObject\ProfileGender;
use Sample\Domain\Models\Profile\ValueObject\ProfileId;
use Sample\Domain\Models\Profile\ValueObject\ProfileImage;
use Sample\Domain\Models\Profile\ValueObject\ProfileName;

/**
 * @property-read ProfileId       $id
 * @property-read ProfileName     $name
 * @property-read ProfileBirthDay $birthDay
 * @property-read ProfileGender   $gender
 * @property-read ProfileImage    $image
 */
interface IProfile extends Domain
{
    public static function definitive(
        ProfileName $name,
        ProfileBirthDay $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): Profile;

    public static function restoreFromDB(
        ProfileId $id,
        ProfileName $name,
        ProfileBirthDay $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): Profile;

    public function changeName(ProfileName $name): void;

    public function changeBirthDay(ProfileBirthDay $birthDay): void;

    public function changeGender(ProfileGender $gender): void;

    public function changeImage(ProfileImage $image): void;
}
