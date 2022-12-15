<?php

declare(strict_types=1);

namespace SampleHR\Unit\Domain;


use SampleHR\Domain\Models\Email\Email;
use SampleHR\Domain\Models\User\Exception\PasswordStrengthException;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserPassword;
use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Tests\ConcreteHash;
use Tests\TestCase;

final class UserTest extends TestCase
{
//    public function test_A()
//    {
//        $noti = new Email(
//            'test@example.com',
//            'test@example.com',
//            'test',
//            'testBody'
//        );
//
//
//        dd($noti);
//    }

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

    public function test_パスワードの変更がハッシュ化されたパスワードでしか変更できないことを確認()
    {
        $noHashPass = UserPassword::of('AAccddssAA1234!3%ja');

        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserPassword::of('AAccddssAA1234!3%ja');
        $user = User::register($email, $pass, $hashService);


        $this->expectException(\TypeError::class);
        $user->changePassword($noHashPass);


        $hashPass = (new ConcreteHash())->hashing(UserPassword::of('AAccddssAA1234!3%ja'));

        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserPassword::of('AAccddssAA1234!3%ja');
        $user = User::register($email, $pass, $hashService);


        $user->changePassword($hashPass);

        self::assertSame($hashPass->value(), $user->userPassword->value());
    }
}

