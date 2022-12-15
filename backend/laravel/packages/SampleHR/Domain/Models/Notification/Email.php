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
     * @return string
     */
    public function getBody(): string;
}
