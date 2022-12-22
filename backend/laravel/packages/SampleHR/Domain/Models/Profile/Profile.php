<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Profile;


use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileGender;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileId;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileImage;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileName;

/**
 * @property-read ProfileId       $id
 * @property-read ProfileName     $name
 * @property-read ProfileBirthDay $birthDay
 * @property-read ProfileGender   $gender
 * @property-read ProfileImage    $image
 */
final class Profile implements Domain
{

    use Getter;

    /**
     * @param ProfileId       $id
     * @param ProfileName     $name
     * @param ProfileBirthDay $birthDay
     * @param ProfileGender   $gender
     * @param ProfileImage    $image
     */
    public function __construct(
        private readonly ProfileId $id,
        private ProfileName $name,
        private ProfileBirthday $birthDay,
        private ProfileGender $gender,
        private ProfileImage $image
    ) {
    }

    /**
     * @return Profile
     * @throws InvalidUuidException|Exception\ProfileInvalidImageUrlException
     */
    public static function temporaryRegister(): Profile
    {
        return new Profile(
            ProfileId::generate(),
            ProfileName::temporaryRegister(),
            ProfileBirthday::of(new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of(),
        );
    }

    public static function restoreFromDB(
        ProfileId $id,
        ProfileName $name,
        ProfileBirthday $birthDay,
        ProfileGender $gender,
        ProfileImage $image
    ): Profile {
        return new Profile(
            $id,
            $name,
            $birthDay,
            $gender,
            $image
        );
    }

    /**
     * 本登録
     *
     * @param Profile $profile
     *
     * @return $this
     */
    public function changeDefinitive(
        Profile $profile
    ): Profile {
        if (!$profile->equals($this)) {
            // TODO  IDが違うプロフィールの例外
        }

        $definitiveProfile = $this;
        $definitiveProfile->changeName($profile->name);
        $definitiveProfile->changeBirthDay($profile->birthDay);
        $definitiveProfile->changeGender($profile->gender);
        $definitiveProfile->changeImage($profile->image);
        return $this;
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
