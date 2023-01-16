<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Model\User;


use Base\DomainSupport\Exception\InvalidEmailAddressException;
use PHPUnit\Framework\TestCase;
use Todo\Domain\Models\Profile\Profile;
use Todo\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Todo\Domain\Models\Profile\ValueObject\ProfileGender;
use Todo\Domain\Models\Profile\ValueObject\ProfileId;
use Todo\Domain\Models\Profile\ValueObject\ProfileImage;
use Todo\Domain\Models\Profile\ValueObject\ProfileName;
use Todo\Domain\Models\User\Exception\PasswordEncryptionException;
use Todo\Domain\Models\User\Exception\PasswordNotChangeException;
use Todo\Domain\Models\User\Exception\PasswordStrengthException;
use Todo\Domain\Models\User\Exception\UserAlreadyDefinitiveException;
use Todo\Domain\Models\User\HashService;
use Todo\Domain\Models\User\User;
use Todo\Domain\Models\User\ValueObject\Password;
use Todo\Domain\Models\User\ValueObject\SocialLoginNoPassword;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Models\User\ValueObject\UserHashPassword;
use Todo\Domain\Models\User\ValueObject\UserId;
use Todo\Domain\Models\User\ValueObject\UserRawPassword;
use Todo\Domain\Models\User\ValueObject\UserStatus;

use function PHPUnit\Framework\assertSame;

final class UserTest extends TestCase
{

    public function test_SNS認証からの仮登録(): void
    {
        $email = UserEmail::of('example@example.com');
        $pass = SocialLoginNoPassword::of();
        $user = User::socialTemporaryRegister($email);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertTrue($pass->value() === $user->userPassword->value());
    }

    public function test_SNS認証の場合パスワードが変更できない(): void
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::socialTemporaryRegister($email);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertFalse($pass->value() === $user->userPassword->value());
        self::assertEquals(UserStatus::Temporary->value(), $user->userStatus->value());

        $name = ProfileName::of('田中', '太郎　');
        $birthDay = ProfileBirthday::of(new \DateTime());
        $gender = ProfileGender::Man;
        $image = ProfileImage::of('https://example.com/test.jpg');

        $profile = Profile::definitive(
            $name,
            $birthDay,
            $gender,
            $image,
        );

        //本登録へ切り替える
        $user->definitiveRegister($profile);

        $this->expectException(PasswordNotChangeException::class);
        $this->expectExceptionMessage(PasswordNotChangeException::MESSAGE);
        $user->changePassword(UserRawPassword::of('ExamplePassword1234!@#$'), $hashService);
    }


    public function test_仮登録(): void
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertFalse($pass->value() === $user->userPassword->value());
    }

    public function test_匿名ユーザの生成(): void
    {
        $user = User::anonymous();

        assertSame(UserStatus::Anonymous, $user->userStatus);
    }

    public function test_仮登録から本登録へ変更(): void
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertFalse($pass->value() === $user->userPassword->value());
        self::assertEquals(UserStatus::Temporary->value(), $user->userStatus->value());

        $name = ProfileName::of('田中', '太郎　');
        $birthDay = ProfileBirthday::of(new \DateTime());
        $gender = ProfileGender::Man;
        $image = ProfileImage::of('https://example.com/test.jpg');

        $profile = Profile::definitive(
            $name,
            $birthDay,
            $gender,
            $image,
        );

        //本登録へ切り替える
        $user = $user->definitiveRegister($profile);

        self::assertEquals(UserStatus::Definitive->value(), $user->userStatus->value());
    }

    public function test_本登録状態から本登録に変更すると例外が発生(): void
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertFalse($pass->value() === $user->userPassword->value());
        self::assertEquals(UserStatus::Temporary->value(), $user->userStatus->value());


        $name = ProfileName::of('田中', '太郎　');
        $birthDay = ProfileBirthday::of(new \DateTime());
        $gender = ProfileGender::Man;
        $image = ProfileImage::of('https://example.com/test.jpg');

        $profile = Profile::definitive(
            $name,
            $birthDay,
            $gender,
            $image,
        );

        //本登録へ切り替える
        $user = $user->definitiveRegister($profile);

        self::assertEquals(UserStatus::Definitive->value(), $user->userStatus->value());

        //再度本登録
        $this->expectException(UserAlreadyDefinitiveException::class);
        $this->expectExceptionMessage(UserAlreadyDefinitiveException::MESSAGE);
        $user = $user->definitiveRegister($profile);
    }

    public function test_適切なパスワードではない(): never
    {
        $this->expectException(PasswordStrengthException::class);
        $this->expectExceptionMessage(PasswordStrengthException::MESSAGE);
        $pass = UserRawPassword::of('123');
        $this->fail('パスワードの設定がおかしくなっています。');
    }

    public function test_適切なメールアドレスが設定されていない(): never
    {
        $this->expectException(InvalidEmailAddressException::class);
        $this->expectExceptionMessage(InvalidEmailAddressException::MESSAGE);
        $email = UserEmail::of('example@example');
        $this->fail('メールアドレスの設定がおかしくなっています。');
    }

    public function test_パスワードの変更をハッシュ化されていない場合ハッシュ化して変更する(): void
    {
        $noHashPass = UserRawPassword::of('AAccddssAA1234!3%ja');

        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);

        $hashService = new ConcreteHash();

        $user->changePassword($noHashPass, $hashService);

        self::assertNotEquals($noHashPass, $user->userPassword->value());
    }


    public function test_パスワードの変更がハッシュ化されたパスワードで変更できる(): void
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);


        $hashPass = (new ConcreteHash())->hashing(UserRawPassword::of('AAccddssAA1234!3%ja'));


        $user->changePassword($hashPass);

        self::assertSame($hashPass->value(), $user->userPassword->value());
    }

    public function test_永続化からの復帰(): void
    {
        $user = User::restoreFromDB(
            UserId::generate(),
            UserEmail::of('example@example.com'),
            UserHashPassword::of('example'),
            UserStatus::Unsubscribe,
            null,
        );

        self::assertInstanceOf(User::class, $user);

        $profile = Profile::restoreFromDB(
            ProfileId::generate(),
            ProfileName::of('exampleName', 'exampleFirst'),
            ProfileBirthDay::of(new \DateTime()),
            ProfileGender::Other,
            ProfileImage::of('https://example.com/image.jpg')
        );

        $user = User::restoreFromDB(
            UserId::generate(),
            UserEmail::of('example@example.com'),
            UserHashPassword::of('example'),
            UserStatus::Unsubscribe,
            $profile,
        );


        self::assertInstanceOf(User::class, $user);
        self::assertInstanceOf(Profile::class, $user->profile);
    }

    public function test_ユーザ同士で同じIDの比較(): void
    {
        $user = User::restoreFromDB(
            UserId::generate(),
            UserEmail::of('example@example.com'),
            UserHashPassword::of('example'),
            UserStatus::Unsubscribe,
            null,
        );

        self::assertTrue($user->equals($user));
    }

    public function test_別のユーザの比較(): void
    {
        $user = User::restoreFromDB(
            UserId::generate(),
            UserEmail::of('example@example.com'),
            UserHashPassword::of('example'),
            UserStatus::Unsubscribe,
            null,
        );


        $diffUser = User::restoreFromDB(
            UserId::generate(),
            UserEmail::of('example@example.com'),
            UserHashPassword::of('example'),
            UserStatus::Unsubscribe,
            null,
        );

        self::assertFalse($user->equals($diffUser));
    }

    public function test_二重でパスワードをハッシュ化しようとすると例外出ることを確認(): void
    {
        $user = User::restoreFromDB(
            UserId::generate(),
            UserEmail::of('example@example.com'),
            UserHashPassword::of('example'),
            UserStatus::Unsubscribe,
            null,
        );

        $this->expectException(PasswordEncryptionException::class);
        $this->expectExceptionMessage(PasswordEncryptionException::MESSAGE);

        $reflection = new \ReflectionClass($user);
        $method = $reflection->getMethod('changeHashPassword');
        $method->setAccessible(true);
        $result = $method->invoke($user, new ConcreteHash());
    }

    public function test_SNS認証の状態でパスワードをハッシュ化しようとすると例外が発生(): void
    {
        $email = UserEmail::of('example@example.com');
        $pass = SocialLoginNoPassword::of();
        $user = User::socialTemporaryRegister($email);

        $this->expectException(PasswordNotChangeException::class);
        $this->expectExceptionMessage(PasswordNotChangeException::MESSAGE);

        $reflection = new \ReflectionClass($user);
        $method = $reflection->getMethod('changeHashPassword');
        $method->setAccessible(true);
        $result = $method->invoke($user, new ConcreteHash());
    }

    public function test_退会処理(): void
    {
        $email = UserEmail::of('example@example.com');
        $pass = SocialLoginNoPassword::of();
        $user = User::socialTemporaryRegister($email);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertTrue($pass->value() === $user->userPassword->value());

        $user = $user->unsubscribe();

        self::assertSame(UserStatus::Unsubscribe, $user->userStatus);
    }

    public function test_ステータス比較(): void
    {
        self::assertTrue(UserStatus::Unsubscribe->equals(UserStatus::Unsubscribe));
        self::assertTrue(UserStatus::Temporary->equals(UserStatus::Temporary));
        self::assertTrue(UserStatus::Ban->equals(UserStatus::Ban));
        self::assertTrue(UserStatus::Definitive->equals(UserStatus::Definitive));
    }

    public function test_ステータスの詳細情報取得(): void
    {
        $r = new \ReflectionEnum(UserStatus::class);
        foreach ($r->getCases() as $case){
            /** @var UserStatus $enum */
            $enum = $case->getValue();
            assertSame($enum->description(), UserStatus::of($case->getValue()->name)->description());
        }
    }

    public function test_パスワード比較(): void
    {
        $password = UserRawPassword::of('Example1234!@#');
        self::assertTrue(UserRawPassword::of('Example1234!@#')->equals($password));
    }


    public function test_ステータス変更(): void
    {
        $email = UserEmail::of('example@example.com');
        $pass = SocialLoginNoPassword::of();
        $user = User::socialTemporaryRegister($email);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertTrue($pass->value() === $user->userPassword->value());

        $user = $user->changeStatus(UserStatus::Ban);

        assertSame(UserStatus::Ban, $user->userStatus);
    }
}

class ConcreteHash implements HashService
{

    public function hashing(Password $raw):UserHashPassword
    {
        return UserHashPassword::of(hash('sha256', (string) $raw->value()));
    }
}
