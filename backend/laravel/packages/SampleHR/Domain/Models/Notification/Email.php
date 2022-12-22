<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Notification;

/**
 *
 */
interface Email
{
    /**
     * @return string
     */
    public function getEmailAddress(): string;

    /**
     * @return string
     */
    public function getFromEmailAddress(): string;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @return array|string
     */
    public function getBody(): array|string;
}
