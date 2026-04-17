<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\Profile\Exception\ProfileBirthdayException;
use Sample\Domain\Models\Profile\ValueObject\ProfileBirthDay;

final class ProfileBirthDayTest extends TestCase
{
    public function test_過去日付で生成できる()
    {
        $date = new \DateTime('-25 years');
        $birthDay = ProfileBirthDay::of($date);
        self::assertSame($date->format(\DateTimeInterface::ISO8601), $birthDay->value());
    }

    public function test_1秒前の日付で生成できる()
    {
        $date = (new \DateTime())->modify('-1 second');
        $birthDay = ProfileBirthDay::of($date);
        self::assertSame($date->format(\DateTimeInterface::ISO8601), $birthDay->value());
    }

    public function test_未来日時だと例外が発生する()
    {
        $this->expectException(ProfileBirthdayException::class);
        ProfileBirthDay::of(new \DateTime('+1 day'));
    }

    public function test_同じ日付は等しい()
    {
        $date = new \DateTime('-25 years');
        $birthDay1 = ProfileBirthDay::of($date);
        $birthDay2 = ProfileBirthDay::of($date);
        self::assertTrue($birthDay1->equals($birthDay2));
    }

    public function test_違う日付は等しくない()
    {
        $birthDay1 = ProfileBirthDay::of(new \DateTime('-25 years'));
        $birthDay2 = ProfileBirthDay::of(new \DateTime('-30 years'));
        self::assertFalse($birthDay1->equals($birthDay2));
    }

    public function test_valueでDateTimeオブジェクトを取得できる()
    {
        $date = new \DateTime('-25 years');
        $birthDay = ProfileBirthDay::of($date);
        $result = $birthDay->value(true);
        self::assertInstanceOf(\DateTime::class, $result);
        self::assertSame($date->getTimestamp(), $result->getTimestamp());
    }
}
