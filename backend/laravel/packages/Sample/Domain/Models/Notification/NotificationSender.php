<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Notification;

/**
 *
 */
interface NotificationSender
{
    public function send(Notification $notification): void;
}
