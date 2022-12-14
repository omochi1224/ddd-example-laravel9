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
     * @param string $value
     *
     * @return static
     */
    public static function of(string $value): static
    {
        return new static($value);
    }

    /**
     * @param ValueObject $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
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
