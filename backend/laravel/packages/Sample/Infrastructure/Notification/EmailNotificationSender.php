<?php

declare(strict_types=1);

namespace Sample\Infrastructure\Notification;

use Sample\Domain\Models\Notification\Notification;
use Sample\Domain\Models\Notification\NotificationSender;

/**
 *
 */
final class EmailNotificationSender implements NotificationSender
{
    /**
     * @param Notification $notification
     *
     * @return void
     */
    public function send(Notification $notification): void
    {
    }
}
