<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User;

use Sample\Domain\Models\Email\Email;

/**
 * ユーザを新規登録すると通知するものです。
 */
final readonly class UserRegisterNotify extends Email
{
    /**
     *
     */
    private const FROM = 'sender@example.com';

    /**
     *
     */
    private const SUBJECT = '新規登録ありがとうございます。';

    /**
     * @param IUser $user
     */
    private function __construct(IUser $user)
    {
        parent::__construct(
            self::FROM,
            $user->userEmail->value(),
            self::SUBJECT,
            '新規登録ありがとうございます。',
        );
    }

    /**
     * @param IUser $user
     *
     * @return UserRegisterNotify
     */
    public static function of(IUser $user): UserRegisterNotify
    {
        return new UserRegisterNotify($user);
    }
}
