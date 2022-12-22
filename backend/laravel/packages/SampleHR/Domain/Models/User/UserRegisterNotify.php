<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\User;

use SampleHR\Domain\Models\Email\Email;

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
     * @param User $user
     */
    private function __construct(User $user)
    {
        parent::__construct(
            self::FROM,
            $user->userEmail->value(),
            self::SUBJECT,
            '新規登録ありがとうございます。',
        );
    }

    /**
     * @param User $user
     *
     * @return UserRegisterNotify
     */
    public static function of(User $user): UserRegisterNotify
    {
        return new UserRegisterNotify($user);
    }
}
