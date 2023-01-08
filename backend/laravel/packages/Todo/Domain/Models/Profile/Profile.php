<?php

declare(strict_types=1);

namespace Todo\Domain\Models\Profile;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
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
final class Profile implements IProfile
{
    use Getter;

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
     *
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

    public function equals(self|Domain $domain): bool
    {
        return $this->id->equals($domain->id);
    }

    public function changeName(ProfileName $name): void
    {
        $this->name = $name;
    }

    public function changeBirthDay(ProfileBirthDay $birthDay): void
    {
        $this->birthDay = $birthDay;
    }

    public function changeGender(ProfileGender $gender): void
    {
        $this->gender = $gender;
    }

    public function changeImage(ProfileImage $image): void
    {
        $this->image = $image;
    }
}
