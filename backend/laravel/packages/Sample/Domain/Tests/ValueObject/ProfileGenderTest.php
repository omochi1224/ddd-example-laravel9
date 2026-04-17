<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\Profile\Exception\ProfileGenderException;
use Sample\Domain\Models\Profile\ValueObject\ProfileGender;

final class ProfileGenderTest extends TestCase
{
    public function test_各性別の値を取得できる()
    {
        self::assertSame(0, ProfileGender::Woman->value());
        self::assertSame(1, ProfileGender::Man->value());
        self::assertSame(2, ProfileGender::Other->value());
    }

    /**
     * @dataProvider validGenderProvider
     */
    public function test_ofで整数値から生成できる(int $value, ProfileGender $expected)
    {
        self::assertSame($expected, ProfileGender::of($value));
    }

    public static function validGenderProvider(): array
    {
        return [
            '女性' => [0, ProfileGender::Woman],
            '男性' => [1, ProfileGender::Man],
            'その他' => [2, ProfileGender::Other],
        ];
    }

    public function test_ofに不正値を渡すと例外が発生する()
    {
        $this->expectException(ProfileGenderException::class);
        ProfileGender::of(99);
    }

    public function test_同じ性別は等しい()
    {
        self::assertTrue(ProfileGender::Woman->equals(ProfileGender::Woman));
        self::assertTrue(ProfileGender::Man->equals(ProfileGender::Man));
    }

    public function test_違う性別は等しくない()
    {
        self::assertFalse(ProfileGender::Woman->equals(ProfileGender::Man));
    }
}
