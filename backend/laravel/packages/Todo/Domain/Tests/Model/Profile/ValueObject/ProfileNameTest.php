<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Model\Profile\ValueObject;

use Base\DomainSupport\ValueObject\ValueObject;
use PHPUnit\Framework\TestCase;
use Todo\Domain\Models\Profile\ValueObject\ProfileName;

final class ProfileNameTest extends TestCase
{
    public function test_比較(): void
    {
        $old = ProfileName::of('last','first');
        $new = ProfileName::of('last','first');

        self::assertTrue($old->equals($new));
    }

    public function test_名前が異なる比較(): void
    {
        $old = ProfileName::of('last','first');

        $new = ProfileName::of('last2','first2');
        self::assertFalse($old->equals($new));

        $new = ProfileName::of('last2','first');
        self::assertFalse($old->equals($new));

        $new = ProfileName::of('last','first2');
        self::assertFalse($old->equals($new));
    }

    public function test_不正なValueObjectが入るとTypeErrorになる(): void
    {
        $name = ProfileName::of('last','first');

        $this->expectException(\TypeError::class);
        $name->equals(new DevilValueObject());
    }
}

class DevilValueObject implements ValueObject
{
    /**
     * @param ValueObject $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
    }

    /**
     * @return mixed
     */
    public function value(): mixed
    {
    }
}
