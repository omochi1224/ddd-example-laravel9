<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Notification;


use SampleHR\Domain\Models\Notification\Email;
use SampleHR\Domain\Models\Notification\Notification;
use SampleHR\Domain\Models\Notification\NotificationSender;
use SampleHR\Presentation\Sender\Sender;

final class EmailNotificationSender implements NotificationSender
{
    public function send(Notification $notification): void
    {
        if($notification instanceof Email) {
            $noti = $this->email($notification);
        }

    }

    private function email(Email $email): Email
    {

    }
}
