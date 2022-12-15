<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Notification;


interface NotificationSender
{
    public function send(Notification $notification): void;
}
