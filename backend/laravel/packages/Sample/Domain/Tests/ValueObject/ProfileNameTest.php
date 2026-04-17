<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\Profile\ValueObject\ProfileName;

final class ProfileNameTest extends TestCase
{
    public function test_姓名を結合して取得できる()
    {
        $name = ProfileName::of('田中', '太郎');
        $value = $name->value();
        self::assertSame('田中', $value['lastName']);
        self::assertSame('太郎', $value['firstName']);
    }

    public function test_同じ姓名は等しい()
    {
        $name1 = ProfileName::of('田中', '太郎');
        $name2 = ProfileName::of('田中', '太郎');
        self::assertTrue($name1->equals($name2));
    }

    public function test_違う姓名は等しくない()
    {
        $name1 = ProfileName::of('田中', '太郎');
        $name2 = ProfileName::of('鈴木', '次郎');
        self::assertFalse($name1->equals($name2));
    }

    public function test_姓が異なる場合は等しくない()
    {
        $name1 = ProfileName::of('田中', '太郎');
        $name2 = ProfileName::of('鈴木', '太郎');
        self::assertFalse($name1->equals($name2));
    }

    public function test_名が異なる場合は等しくない()
    {
        $name1 = ProfileName::of('田中', '太郎');
        $name2 = ProfileName::of('田中', '次郎');
        self::assertFalse($name1->equals($name2));
    }
}
