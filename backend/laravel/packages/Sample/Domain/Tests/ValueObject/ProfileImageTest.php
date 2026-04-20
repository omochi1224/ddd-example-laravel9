<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\ValueObject;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;
use Sample\Domain\Models\Profile\ValueObject\ProfileImage;

final class ProfileImageTest extends TestCase
{
    public function test_有効なURLで生成できる()
    {
        $image = ProfileImage::of('https://example.com/user1.jpg');
        self::assertSame('https://example.com/user1.jpg', $image->value());
    }

    public function test_デフォルトで未設定画像URLが設定される()
    {
        $image = ProfileImage::of();
        self::assertSame('https://example.com/noimage', $image->value());
    }

    public function test_banメソッドでBAN専用画像を生成できる()
    {
        $image = ProfileImage::ban();
        self::assertSame('https://example.com/ban', $image->value());
    }

    public function test_無効なURLだと例外が発生する()
    {
        $this->expectException(ProfileInvalidImageUrlException::class);
        ProfileImage::of('invalid-url');
    }

    public function test_空文字URLだと例外が発生する()
    {
        $this->expectException(ProfileInvalidImageUrlException::class);
        ProfileImage::of('');
    }

    #[DataProvider('invalidUrlProvider')]
    public function test_不正なURL形式は全て例外(string $url)
    {
        $this->expectException(ProfileInvalidImageUrlException::class);
        ProfileImage::of($url);
    }

    public static function invalidUrlProvider(): array
    {
        return [
            'ただの文字列' => ['not-a-url'],
            'スペースのみ' => ['   '],
        ];
    }
}
