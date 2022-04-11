<?php

declare(strict_types=1);

namespace Common\Infrastructure\Sender\Mailable;

use App\Mail\RegisterMail;
use Common\Domain\Models\Email\Email;
use Common\Domain\Models\Email\EmailSender;
use Common\Domain\Models\Email\ValueObject\EmailTemplate;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 *
 */
final class MailableEmailSender implements EmailSender
{
    /**
     * @param Email $email
     *
     * @return void
     */
    public function send(Email $email): void
    {
        Mail::to($email->emailTo->value())->send($this->changeEmail($email));
    }

    /**
     * @param Email $email
     *
     * @return Mailable
     */
    private function changeEmail(Email $email): Mailable
    {
        return match ($email->emailTemplate) {
            EmailTemplate::REGI => new RegisterMail(),
        };
    }
}
