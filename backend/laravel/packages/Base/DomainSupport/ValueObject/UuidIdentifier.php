<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

use Base\DomainSupport\Exception\InvalidUuidException;
use Exception;

/**
 * UUID識別子根底抽象クラス
 */

readonly abstract class UuidIdentifier implements ValueObject
{
    private const PATTERN = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    /**
     * @var string
     */
    private string $value;

    /**
     * @throws InvalidUuidException
     */
    final public function __construct(string $value)
    {
        if (preg_match(self::PATTERN, $value) !== 1) {
            throw new InvalidUuidException();
        }

        $this->value = $value;
    }

    /**
     * @throws InvalidUuidException
     */
    final public static function of(string $value): static
    {
        return new static($value);
    }

    final public function equals(ValueObject $valueObject): bool
    {
        return $valueObject->value() === $this->value();
    }

    final public function value(): string
    {
        return $this->value;
    }

    /**
     * UUID 生成
     *
     *
     * @throws InvalidUuidException
     * @throws Exception
     */
    final public static function generate(): static
    {
        $uuid = preg_replace_callback(
            '/x|y/',
            function($m) {
                return dechex($m[0] === 'x' ? random_int(0, 15) : random_int(8, 11));
            },
            'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'
        );

        return new static($uuid);
    }
}
