<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

/**
 * 数値根底抽象クラス
 */
abstract class IntValueObject implements ValueObject
{
    public function __construct(private readonly int $value)
    {
    }

    public function equals(ValueObject $valueObject): bool
    {
        return $valueObject->value() === $this->value();
    }

    public function value(): int
    {
        return $this->value;
    }
}
