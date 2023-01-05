<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Email;

use Base\DomainSupport\Domain\Getter;
use Sample\Domain\Models\Notification\Email as IEmail;
use Sample\Domain\Models\Notification\Notification;

/**
 *
 */
abstract readonly class Email implements Notification, IEmail
{
    use Getter;

    /**
     * @param string       $toEmailAddress
     * @param string       $fromEmailAddress
     * @param string       $subject
     * @param array<mixed>|string $body
     */
    public function __construct(
        private string $toEmailAddress,
        private string $fromEmailAddress,
        private string $subject,
        private array|string $body,
    ) {
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->toEmailAddress;
    }

    /**
     * @return string
     */
    public function getFromEmailAddress(): string
    {
        return $this->fromEmailAddress;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return array<mixed>|string
     */
    public function getBody(): array|string
    {
        return $this->body;
    }
}
