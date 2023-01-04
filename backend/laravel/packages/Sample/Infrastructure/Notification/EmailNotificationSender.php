<?php

declare(strict_types=1);

namespace Sample\Infrastructure\Notification;

use Sample\Domain\Models\Notification\Email;
use Sample\Domain\Models\Notification\Notification;
use Sample\Domain\Models\Notification\NotificationSender;

final class EmailNotificationSender implements NotificationSender
{
    public function send(Notification $notification): void
    {
        $noti = match (true) {
            $notification instanceof Email => $this->email($notification),
        };

        dd($noti);
    }

    private function email(Email $email): Email
    {
    }
}
