<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Profile\Exception;


use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

/**
 *
 */
final class ProfileBirthdayException extends DomainException
{
    /**
     *
     */
    #[HttpStatusCode(422)]
    public const MESSAGE = '誕生日は現在の日付より前に設定してください。';
}
