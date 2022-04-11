<?php

declare(strict_types=1);

namespace Base\DomainSupport\ValueObject;

use Base\DomainSupport\Exception\InvalidUuidException;
use Exception;

/**
 * UUID識別子根底抽象クラス
 */
abstract class UuidIdentifier implements ValueObject
{
    /**
     * UUIDフォーマット
     */
    private const PATTERN = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';

    /**
     * @var string
     */
    private readonly string $pattern;

    /**
     * @var string
     */
    private readonly string $value;

    /**
     * @throws InvalidUuidException
     */
    public function __construct(string $value)
    {
        $this->pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

        if (preg_match($this->pattern, $value) !== 1) {
            throw new InvalidUuidException();
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @return static
     * @throws InvalidUuidException
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

    /**
     * UUID 生成
     *
     * @return UuidIdentifier
     * @throws InvalidUuidException
     * @throws Exception
     */
    public static function generate(): static
    {
        $chars = str_split(self::PATTERN);

        foreach ($chars as $i => $char) {
            if ($char === 'x') {
                $chars[$i] = dechex(random_int(0, 15));
            } elseif ($char === 'y') {
                $chars[$i] = dechex(random_int(8, 11));
            }
        }

        return new static(implode('', $chars));
    }
}
