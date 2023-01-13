<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Model\Profile\ValueObject;


use PHPUnit\Framework\TestCase;
use Todo\Domain\Models\Profile\ValueObject\ProfileGender;

final class ProfileGenderTest extends TestCase
{
    public function test_比較(): void
    {
        $oldGender = ProfileGender::of(0);
        self::assertTrue(ProfileGender::of(0)->equals($oldGender));

        self::assertFalse(ProfileGender::of(1)->equals($oldGender));
    }

    public function test_値取り出し(): void
    {
        $gender = ProfileGender::of(0);
        self::assertSame(0,$gender->value());
    }
}
