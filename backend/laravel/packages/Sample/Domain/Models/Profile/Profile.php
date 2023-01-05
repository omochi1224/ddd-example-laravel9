<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
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
final class Profile implements IProfile
{
    use Getter;

    /**
     * @param ProfileId       $id
     * @param ProfileName     $name
     * @param ProfileBirthDay $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     */
    private function __construct(
        private readonly ProfileId $id,
        private ProfileName $name,
        private ProfileBirthDay $birthDay,
        private ProfileGender $gender,
        private ProfileImage $image,
    ) {
    }

    /**
     * 本登録用のプロフィール作成
     *
     * @param ProfileName     $name
     * @param ProfileBirthDay $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     *
     * @return Profile
     *
     * @throws InvalidUuidException
     */
    public static function definitive(
        ProfileName $name,
        ProfileBirthDay $birthDay,
        ProfileGender $gender,
        ProfileImage $image,
    ): Profile {
        return new Profile(
            ProfileId::generate(),
            $name,
            $birthDay,
            $gender,
            $image,
        );
    }

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
    ): Profile {
        return new Profile(
            $id,
            $name,
            $birthDay,
            $gender,
            $image,
        );
    }

    /**
     * @param Profile $domain
     *
     * @return bool
     */
    public function equals(Domain $domain): bool
    {
        return $this->id->equals($domain->id);
    }

    /**
     * @param ProfileName $name
     */
    public function changeName(ProfileName $name): void
    {
        $this->name = $name;
    }

    /**
     * @param ProfileBirthDay $birthDay
     */
    public function changeBirthDay(ProfileBirthDay $birthDay): void
    {
        $this->birthDay = $birthDay;
    }

    /**
     * @param ProfileGender $gender
     */
    public function changeGender(ProfileGender $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @param ProfileImage $image
     */
    public function changeImage(ProfileImage $image): void
    {
        $this->image = $image;
    }
}
