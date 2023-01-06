<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\Model;


use Sample\Domain\Models\Profile\Profile;
use Sample\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Sample\Domain\Models\Profile\ValueObject\ProfileGender;
use Sample\Domain\Models\Profile\ValueObject\ProfileId;
use Sample\Domain\Models\Profile\ValueObject\ProfileImage;
use Sample\Domain\Models\Profile\ValueObject\ProfileName;
use Tests\TestCase;

final class ProfileTest extends TestCase
{
    public function test_永続化から復帰()
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

    public function test_プロフィールの比較()
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

    public function test_違うIDのプロフィールの比較()
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


    public function test_誕生日変更()
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
}
