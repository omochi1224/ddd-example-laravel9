<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Notification;

use SampleHR\Domain\Models\Notification\Email;
use SampleHR\Domain\Models\Notification\Notification;
use SampleHR\Domain\Models\Notification\NotificationSender;

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
