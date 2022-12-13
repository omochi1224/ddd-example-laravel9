<?php

declare(strict_types=1);

namespace Auth\Unit\Domain;


use Auth\Domain\Models\User\Exception\PasswordStrengthException;
use Auth\Domain\Models\User\HashService;
use Auth\Domain\Models\User\User;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Auth\Domain\Models\User\ValueObject\UserPassword;
use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Base\DomainSupport\ValueObject\StringValueObject;
use Tests\ConcreteHash;
use Tests\TestCase;

final class UserTest extends TestCase
{
    public function test_正常()
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserPassword::of('AAccddssAA1234!3%ja');
        $user = User::register($email, $pass, $hashService);


        self::assertSame($email->value(), $user->userEmail->value());
        self::assertFalse($pass->value() === $user->userPassword->value());
    }

    public function test_適切なパスワードではない()
    {
        $email = UserEmail::of('example@example.com');

        $this->expectException(PasswordStrengthException::class);
        $this->expectExceptionMessage(PasswordStrengthException::MESSAGE);

        $pass = UserPassword::of('123');
        $this->fail('パスワードの設定がおかしくなっています。');
    }

    public function test_適切なメールアドレスが設定されていない()
    {

        $this->expectException(InvalidEmailAddressException::class);
        $this->expectExceptionMessage(InvalidEmailAddressException::MESSAGE);
        $email = UserEmail::of('example@example');
        $this->fail('メールアドレスの設定がおかしくなっています。');
    }

}

