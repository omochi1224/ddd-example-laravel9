<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

/**
 * 文字列根底抽象クラス
 */

readonly abstract class StringValueObject implements ValueObject
{
    /**
     * @param string $value
     */
    public function __construct(protected string $value)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public static function of(string $value): static
    {
        return new static($value);
    }

    /**
     * @param StringValueObject|ValueObject $valueObject
     *
     * @return bool
     */
    public function equals(StringValueObject|ValueObject $valueObject): bool
    {
        return $valueObject->value() === $this->value();
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
