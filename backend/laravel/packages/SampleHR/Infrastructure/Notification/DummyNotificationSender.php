<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Notification;

use Illuminate\Support\Facades\Log;
use SampleHR\Domain\Models\Notification\Notification;
use SampleHR\Domain\Models\Notification\NotificationSender;

final class DummyNotificationSender implements NotificationSender
{
    /**
     * @param Notification $notification
     *
     * @return void
     */
    public function send(Notification $notification): void
    {
        $n = $notification;
        Log::debug('');
    }
}
