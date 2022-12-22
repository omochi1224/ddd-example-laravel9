<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Notification;

/**
 *
 */
interface NotificationSender
{
    /**
     * @param Notification $notification
     *
     * @return void
     */
    public function send(Notification $notification): void;
}
