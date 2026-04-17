<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class ProfileInvalidImageUrlException extends DomainException
{
    #[HttpStatusCode(422)]
    public const MESSAGE = '無効なURLです。';
}
