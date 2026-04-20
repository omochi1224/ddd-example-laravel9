<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\ValueObject;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\User\ValueObject\UserStatus;

final class UserStatusTest extends TestCase
{
    public function test_各ステータスの値を取得できる()
    {
        self::assertSame(0, UserStatus::Temporary->value());
        self::assertSame(100, UserStatus::Definitive->value());
        self::assertSame(400, UserStatus::Ban->value());
        self::assertSame(900, UserStatus::Unsubscribe->value());
    }

    public function test_各ステータスの説明文を取得できる()
    {
        self::assertSame('仮登録', UserStatus::Temporary->description());
        self::assertSame('本登録', UserStatus::Definitive->description());
        self::assertSame('禁止', UserStatus::Ban->description());
        self::assertSame('退会', UserStatus::Unsubscribe->description());
    }

    public function test_valueメソッドで値を取得できる()
    {
        self::assertSame(0, UserStatus::Temporary->value());
        self::assertSame(100, UserStatus::Definitive->value());
    }

    #[DataProvider('fromProvider')]
    public function test_fromで文字列から生成できる(string $name, UserStatus $expected)
    {
        self::assertSame($expected, UserStatus::of($name));
    }

    public static function fromProvider(): array
    {
        return [
            '仮登録' => ['Temporary', UserStatus::Temporary],
            '本登録' => ['Definitive', UserStatus::Definitive],
            '退会' => ['Unsubscribe', UserStatus::Unsubscribe],
            '禁止' => ['Ban', UserStatus::Ban],
        ];
    }

    public function test_fromに不正値を渡すと例外が発生する()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unexpected match value');
        UserStatus::of('Invalid');
    }

    public function test_同じステータスは等しい()
    {
        self::assertTrue(UserStatus::Temporary->equals(UserStatus::Temporary));
        self::assertTrue(UserStatus::Ban->equals(UserStatus::Ban));
    }

    public function test_違うステータスは等しくない()
    {
        self::assertFalse(UserStatus::Temporary->equals(UserStatus::Ban));
        self::assertFalse(UserStatus::Definitive->equals(UserStatus::Unsubscribe));
    }

    public function test_backed_enumのfromで整数値から生成できる()
    {
        self::assertSame(UserStatus::Temporary, UserStatus::from(0));
        self::assertSame(UserStatus::Definitive, UserStatus::from(100));
        self::assertSame(UserStatus::Ban, UserStatus::from(400));
        self::assertSame(UserStatus::Unsubscribe, UserStatus::from(900));
    }
}
