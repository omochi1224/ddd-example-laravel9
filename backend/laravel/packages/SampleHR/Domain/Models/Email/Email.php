<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Email;


use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use SampleHR\Domain\Models\Notification\Notification;
use SampleHR\Domain\Models\Notification\Email as IEmail;


readonly final class Email implements Notification, IEmail
{
    use Getter;

    public function __construct(
        private string $toEmailAddress,
        private string $fromEmailAddress,
        private string $subject,
        private string $body,
    ){
    }

    /**
     * @param Domain $domain
     *
     * @return bool
     */
    public function equals(Domain $domain): bool
    {
        return false;
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
     * @return string
     */
    public function getBody(): string
    {
        // TODO: Implement getBody() method.
    }
}
