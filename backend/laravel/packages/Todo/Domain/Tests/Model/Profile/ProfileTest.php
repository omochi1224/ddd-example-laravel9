<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Model\Profile;


use Tests\TestCase;
use Todo\Domain\Models\Profile\Profile;
use Todo\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Todo\Domain\Models\Profile\ValueObject\ProfileGender;
use Todo\Domain\Models\Profile\ValueObject\ProfileId;
use Todo\Domain\Models\Profile\ValueObject\ProfileImage;
use Todo\Domain\Models\Profile\ValueObject\ProfileName;

use function PHPUnit\Framework\assertSame;

final class ProfileTest extends TestCase
{
    public function test_永続化から復帰(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        self::assertInstanceOf(Profile::class, $profile);
    }

    public function test_プロフィールの比較(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        self::assertTrue($profile->equals($profile));
    }

    public function test_違うIDのプロフィールの比較(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        $diffProfile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );


        self::assertFalse($profile->equals($diffProfile));
    }


    public function test_誕生日変更(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of($date = new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );
        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));

        $changeBirthday = ProfileBirthDay::of($date = new \DateTime());
        $profile->changeBirthDay($changeBirthday);

        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));
    }


        public function test_名前変更(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of($date = new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );
        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));

        $profile->changeName(ProfileName::of('change', 'name'));

        self::assertSame(['lastName'=>'change', 'firstName'=>'name'], $profile->name->value());
    }

        public function test_性別(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of($date = new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );
        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));

        $profile->changeGender(ProfileGender::Man);

        assertSame(ProfileGender::Man, $profile->gender);
    }

        public function test_プロフィール画像変更(): void
    {
        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of($date = new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );
        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));

        $profile->changeImage(ProfileImage::of('https://example.com/change-image.jpg'));

        assertSame('https://example.com/change-image.jpg', $profile->image->value());
    }
}
