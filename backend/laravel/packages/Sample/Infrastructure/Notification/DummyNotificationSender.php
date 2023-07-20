<?php

declare(strict_types=1);

namespace Sample\Infrastructure\Notification;

use Illuminate\Support\Facades\Log;
use Sample\Domain\Models\Notification\Notification;
use Sample\Domain\Models\Notification\NotificationSender;

final class DummyNotificationSender implements NotificationSender
{
    public function send(Notification $notification): void
    {
        $n = $notification;
        Log::debug('');
    }
}
