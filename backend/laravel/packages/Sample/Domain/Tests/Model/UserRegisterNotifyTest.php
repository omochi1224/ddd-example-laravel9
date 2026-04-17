<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\Model;

use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Base\DomainSupport\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\User\HashService;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRegisterNotify;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserHashPassword;
use Sample\Domain\Models\User\ValueObject\UserRawPassword;

final class UserRegisterNotifyTest extends TestCase
{
    private function createUser(string $email): User
    {
        return User::temporaryRegister(
            UserEmail::of($email),
            UserRawPassword::of('Password!1234@pAssword'),
            new ConcreteHashForNotify()
        );
    }

    public function test_通知の送信先がユーザのメールアドレスと同じ()
    {
        $notify = UserRegisterNotify::of($this->createUser('user@example.com'));
        self::assertSame('user@example.com', $notify->getEmailAddress());
    }

    public function test_送信元が固定値()
    {
        $notify = UserRegisterNotify::of($this->createUser('user@example.com'));
        self::assertSame('sender@example.com', $notify->getFromEmailAddress());
    }

    public function test_件名が固定値()
    {
        $notify = UserRegisterNotify::of($this->createUser('user@example.com'));
        self::assertSame('新規登録ありがとうございます。', $notify->getSubject());
    }

    public function test_本文が設定されている()
    {
        $notify = UserRegisterNotify::of($this->createUser('user@example.com'));
        self::assertSame('新規登録ありがとうございます。', $notify->getBody());
    }

    public function test_Notificationインターフェースを実装している()
    {
        $notify = UserRegisterNotify::of($this->createUser('user@example.com'));
        self::assertInstanceOf(\Sample\Domain\Models\Notification\Notification::class, $notify);
        self::assertInstanceOf(\Sample\Domain\Models\Notification\Email::class, $notify);
    }
}

class ConcreteHashForNotify implements HashService
{
    public function hashing(StringValueObject $raw): UserHashPassword
    {
        return UserHashPassword::of(hash('sha256', $raw->value()));
    }
}
