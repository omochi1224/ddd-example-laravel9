<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile;

use Base\DomainSupport\Domain\Domain;
use Sample\Domain\Models\Profile\ValueObject\ProfileBirthday;
use Sample\Domain\Models\Profile\ValueObject\ProfileGender;
use Sample\Domain\Models\Profile\ValueObject\ProfileId;
use Sample\Domain\Models\Profile\ValueObject\ProfileImage;
use Sample\Domain\Models\Profile\ValueObject\ProfileName;

/**
 * @property-read ProfileId       $id
 * @property-read ProfileName     $name
 * @property-read ProfileBirthday $birthDay
 * @property-read ProfileGender   $gender
 * @property-read ProfileImage    $image
 */
interface IProfile extends Domain
{
    /**
     * @param ProfileName     $name
     * @param ProfileBirthday $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     *
     * @return Profile
     */
    public static function definitive(
        ProfileName $name,
        ProfileBirthday $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): Profile;

    /**
     * @param ProfileId       $id
     * @param ProfileName     $name
     * @param ProfileBirthday $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     *
     * @return Profile
     */
    public static function restoreFromDB(
        ProfileId $id,
        ProfileName $name,
        ProfileBirthday $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): Profile;

    /**
     * @param ProfileName $name
     *
     * @return void
     */
    public function changeName(ProfileName $name): void;

    /**
     * @param ProfileBirthday $birthDay
     *
     * @return void
     */
    public function changeBirthDay(ProfileBirthday $birthDay): void;

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
