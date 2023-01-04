<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile\ValueObject;

use Base\DomainSupport\ValueObject\StringValueObject;
use Sample\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;

/**
 *
 */
final readonly class ProfileImage extends StringValueObject
{
    /**
     * 未設定画像URL
     */
    private const EMPTY_IMAGE_URL = 'https://example.com/noimage';

    /**
     * BAN専用画像
     */
    private const BAN_IMAGE_URL = 'https://example.com/ban';

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
     * @return $this
     *
     * @throws ProfileInvalidImageUrlException
     */
    public static function of(string $value = self::EMPTY_IMAGE_URL): static
    {
        return new ProfileImage($value);
    }

    /**
     * @return $this
     *
     * @throws ProfileInvalidImageUrlException
     */
    public static function ban(): ProfileImage
    {
        return ProfileImage::of(self::BAN_IMAGE_URL);
    }
}
