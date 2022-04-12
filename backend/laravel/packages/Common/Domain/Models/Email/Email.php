<?php

declare(strict_types=1);

namespace Common\Domain\Models\Email;

use Base\DomainSupport\Domain\Getter;
use Common\Domain\Models\Email\ValueObject\EmailSubject;
use Common\Domain\Models\Email\ValueObject\EmailTemplate;
use Common\Domain\Models\Email\ValueObject\EmailText;
use Common\Domain\Models\Email\ValueObject\EmailTo;

/**
 * @property-read EmailTo       $emailTo
 * @property-read EmailSubject  $emailSubject
 * @property-read EmailText     $emailText
 * @property-read EmailTemplate $emailTemplate
 */
abstract class Email
{
    use Getter;

    /**
     * @param EmailTo       $emailTo
     * @param EmailSubject  $emailSubject
     * @param EmailText     $emailText
     * @param EmailTemplate $emailTemplate
     */
    public function __construct(
        private EmailTo $emailTo,
        private EmailSubject $emailSubject,
        private EmailText $emailText,
        private EmailTemplate $emailTemplate,
    ) {
    }
}
