<?php

declare(strict_types=1);

namespace Todo\Domain\Models\Profile;

use Base\DomainSupport\Domain\Domain;
use Todo\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Todo\Domain\Models\Profile\ValueObject\ProfileGender;
use Todo\Domain\Models\Profile\ValueObject\ProfileId;
use Todo\Domain\Models\Profile\ValueObject\ProfileImage;
use Todo\Domain\Models\Profile\ValueObject\ProfileName;

/**
 * @property-read ProfileId       $id
 * @property-read ProfileName     $name
 * @property-read ProfileBirthDay $birthDay
 * @property-read ProfileGender   $gender
 * @property-read ProfileImage    $image
 */
interface IProfile extends Domain
{
    /**
     * @param ProfileName     $name
     * @param ProfileBirthDay $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     *
     * @return Profile
     */
    public static function definitive(
        ProfileName $name,
        ProfileBirthDay $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): IProfile;

    /**
     * @param ProfileId       $id
     * @param ProfileName     $name
     * @param ProfileBirthDay $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     *
     * @return Profile
     */
    public static function restoreFromDB(
        ProfileId $id,
        ProfileName $name,
        ProfileBirthDay $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): IProfile;

    /**
     * @param ProfileName $name
     *
     * @return void
     */
    public function changeName(ProfileName $name): void;

    /**
     * @param ProfileBirthDay $birthDay
     *
     * @return void
     */
    public function changeBirthDay(ProfileBirthDay $birthDay): void;

    /**
     * @param ProfileGender $gender
     *
     * @return void
     */
    public function changeGender(ProfileGender $gender): void;

    /**
     * @param ProfileImage $image
     *
     * @return void
     */
    public function changeImage(ProfileImage $image): void;
}
