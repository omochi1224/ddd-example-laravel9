<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Notification\Exception;


use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class NotificationSenderException extends DomainException
{
    #[HttpStatusCode(500)]
    const MESSAGE = '正常に通知が送れませんでした。';
}
