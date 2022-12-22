<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Profile\ValueObject;


use Base\DomainSupport\ValueObject\StringValueObject;
use SampleHR\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;

/**
 *
 */
final readonly class ProfileImage extends StringValueObject
{
    /**
     * 未設定画像URL
     */
    private const EMPTY_IMAGE_URL = 'https://example.com';

    /**
     * @param string|null $value
     *
     * @throws ProfileInvalidImageUrlException
     */
    private function __construct(?string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new ProfileInvalidImageUrlException(ProfileInvalidImageUrlException::MESSAGE);
        }
        parent::__construct($value);
    }

    /**
     * @param string $value
     *
     * @return static
     * @throws ProfileInvalidImageUrlException
     */
    public static function of(string $value = self::EMPTY_IMAGE_URL): static
    {
        return new ProfileImage($value);
    }
}
