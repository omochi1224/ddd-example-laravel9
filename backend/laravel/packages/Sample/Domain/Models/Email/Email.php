<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Email;

use Base\DomainSupport\Domain\Getter;
use Sample\Domain\Models\Notification\Email as IEmail;
use Sample\Domain\Models\Notification\Notification;

abstract readonly class Email implements Notification, IEmail
{
    use Getter;

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
        // TODO: Implement getEmailAddress() method.
    }

    /**
     * @return string
     */
    public function getFromEmailAddress(): string
    {
        // TODO: Implement getFromEmailAddress() method.
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        // TODO: Implement getSubject() method.
    }

    /**
     * @return array|string
     */
    public function getBody(): array|string
    {
        // TODO: Implement getBody() method.
    }
}
