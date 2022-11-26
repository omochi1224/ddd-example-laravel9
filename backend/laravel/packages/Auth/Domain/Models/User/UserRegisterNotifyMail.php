<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User;

use Auth\Domain\Models\User\ValueObject\AccessToken;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\ValueObject\EmailValueObject;
use Common\Domain\Models\Email\Email;
use Common\Domain\Models\Email\ValueObject\EmailSubject;
use Common\Domain\Models\Email\ValueObject\EmailTemplate;
use Common\Domain\Models\Email\ValueObject\EmailText;
use Common\Domain\Models\Email\ValueObject\EmailTo;

/**
 * 登録時に送るメール
 *
 * @property EmailTo       $emailTo
 * @property EmailSubject  $emailSubject
 * @property EmailText     $emailText
 * @property EmailTemplate $emailTemplate
 * @property AccessToken $accessToken
 */
final class UserRegisterNotifyMail extends Email
{
    use Getter;

    /**
     * @param EmailTo       $emailTo
     * @param EmailSubject  $emailSubject
     * @param EmailText     $emailText
     * @param EmailTemplate $emailTemplate
     * @param AccessToken   $accessToken
     */
    public function __construct(
        EmailTo $emailTo,
        EmailSubject $emailSubject,
        EmailText $emailText,
        EmailTemplate $emailTemplate,
        AccessToken $accessToken
    ) {
//        parent::__construct($emailTo, $emailSubject, $emailText, $emailTemplate);
        $this->emailTo = $emailTo;
        $this->emailSubject = $emailSubject;
        $this->emailText = $emailText;
        $this->emailTemplate = $emailTemplate;
        $this->accessToken = $accessToken;
    }

    /**
     * @param EmailValueObject|EmailTo $emailTo
     *
     * @return UserRegisterNotifyMail
     */
    public static function registerNotificationEmail(EmailValueObject|EmailTo $emailTo): UserRegisterNotifyMail
    {
        $subject = EmailSubject::of('ユーザ仮登録のお知らせ');
        $text = EmailText::of(
            'ユーザの仮登録が完了しました。　こちらのURLをクリックして本登録を済ませてください。 http://example.com
            '
        );

        $emailTo = EmailTo::of($emailTo->value());
        return new UserRegisterNotifyMail($emailTo, $subject, $text, EmailTemplate::REGI, AccessToken::generate());
    }

    /**
     * @param AccessToken $accessToken
     *
     * @return void
     */
    public function changeAccessToken(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }
}
