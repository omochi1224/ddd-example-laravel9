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
    private static function pastDate(): \DateTime
    {
        return (new \DateTime())->modify('-1 year');
    }

    public function test_永続化から復帰()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        self::assertInstanceOf(Profile::class, $profile);
    }

    public function test_プロフィールの比較()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        self::assertTrue($profile->equals($profile));
    }

    public function test_違うIDのプロフィールの比較()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        $diffProfile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );


        self::assertFalse($profile->equals($diffProfile));
    }


    public function test_誕生日変更()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of($date = self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );
        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));

        $changeBirthday = ProfileBirthDay::of($date = self::pastDate());
        $profile->changeBirthDay($changeBirthday);

        self::assertSame($profile->birthDay->value(), $date->format(\DateTimeInterface::ISO8601));
    }

    public function test_definitiveで新規プロフィールを作成できる()
    {
        $profile = Profile::definitive(
            ProfileName::of('山田', '花子'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Woman,
            ProfileImage::of('https://example.com/profile.jpg')
        );

        self::assertInstanceOf(Profile::class, $profile);
        self::assertNotNull($profile->id);
        self::assertSame(['lastName' => '山田', 'firstName' => '花子'], $profile->name->value());
    }

    public function test_名前を変更できる()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('田中', '太郎'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        $profile->changeName(ProfileName::of('鈴木', '次郎'));
        self::assertSame('鈴木', $profile->name->value()['lastName']);
        self::assertSame('次郎', $profile->name->value()['firstName']);
    }

    public function test_性別を変更できる()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('田中', '太郎'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Woman,
            ProfileImage::of('https://example.com/image.jpg')
        );

        $profile->changeGender(ProfileGender::Man);
        self::assertSame(1, $profile->gender->value());
    }

    public function test_画像を変更できる()
    {
        $profile = Profile::restoreFromDb(
            ProfileId::generate(),
            ProfileName::of('田中', '太郎'),
            ProfileBirthDay::of(self::pastDate()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/old.jpg')
        );

        $profile->changeImage(ProfileImage::of('https://example.com/new.jpg'));
        self::assertSame('https://example.com/new.jpg', $profile->image->value());
    }
}
