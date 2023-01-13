<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Model\Profile\ValueObject;


use PHPUnit\Framework\TestCase;
use Todo\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;
use Todo\Domain\Models\Profile\ValueObject\ProfileImage;

final class ProfileImageTest extends TestCase
{
    public function test_正常なURL(): void
    {
        $url = 'https://example.com/image.jpg';

        $image = ProfileImage::of($url);

        self::assertSame($url, $image->value());
    }

    public function test_不正なURLの場合例外が発生(): void
    {
        $url = 'example/image.jpg';

        $this->expectException(ProfileInvalidImageUrlException::class);
        $this->expectExceptionMessage(ProfileInvalidImageUrlException::MESSAGE);

        $image = ProfileImage::of($url);
    }

    public function test_画像URLが設定されていない場合未設定画像を自動で入れる(): void
    {
        $image = ProfileImage::of();


        $r = new \ReflectionClass($image);
        $emptyImageUrl = $r->getConstant('EMPTY_IMAGE_URL');

         self::assertSame($emptyImageUrl, $image->value());
    }

    public function test_BANされた場合自動でBAN画像を入れる(): void
    {
        $image = ProfileImage::ban();

        $r = new \ReflectionClass($image);
        $emptyImageUrl = $r->getConstant('BAN_IMAGE_URL');

         self::assertSame($emptyImageUrl, $image->value());
    }
}
