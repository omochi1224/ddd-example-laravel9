<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Profile\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

/**
 *
 */
final class ProfileInvalidIDException extends DomainException
{
    /**
     *
     */
    #[HttpStatusCode(500)]
    public const MESSAGE = '仮登録のプロフィールIDと合致しません。';
}
