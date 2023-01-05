<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Notification\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class NotificationSenderException extends DomainException
{
    #[HttpStatusCode(500)]
    public const MESSAGE = '正常に通知が送れませんでした。';
}
