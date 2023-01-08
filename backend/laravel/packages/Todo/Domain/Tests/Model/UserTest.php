<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Model;


use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Base\DomainSupport\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;
use Todo\Domain\Models\Profile\Profile;
use Todo\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use Todo\Domain\Models\Profile\ValueObject\ProfileGender;
use Todo\Domain\Models\Profile\ValueObject\ProfileId;
use Todo\Domain\Models\Profile\ValueObject\ProfileImage;
use Todo\Domain\Models\Profile\ValueObject\ProfileName;
use Todo\Domain\Models\User\Exception\PasswordEncryptionException;
use Todo\Domain\Models\User\Exception\PasswordStrengthException;
use Todo\Domain\Models\User\Exception\UserAlreadyDefinitiveException;
use Todo\Domain\Models\User\HashService;
use Todo\Domain\Models\User\User;
use Todo\Domain\Models\User\ValueObject\SocialLoginNoPassword;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Models\User\ValueObject\UserHashPassword;
use Todo\Domain\Models\User\ValueObject\UserId;
use Todo\Domain\Models\User\ValueObject\UserRawPassword;
use Todo\Domain\Models\User\ValueObject\UserStatus;

final class UserTest extends TestCase
{

    public function test_SNS認証からの仮登録()
    {
        $email = UserEmail::of('example@example.com');
        $pass = SocialLoginNoPassword::of();
        $user = User::socialTemporaryRegister($email);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertTrue($pass->value() === $user->userPassword->value());
    }


    public function test_仮登録()
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);

        self::assertSame($email->value(), $user->userEmail->value());
        self::assertFalse($pass->value() === $user->userPassword->value());
    }

    public function test_仮登録から本登録へ変更()
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
        $user->changeDefinitiveRegister($profile);

        self::assertEquals(UserStatus::Definitive->value(), $user->userStatus->value());
    }

    public function test_本登録状態から本登録に変更すると例外が発生()
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

        $user->changeDefinitiveRegister($profile);

        self::assertEquals(UserStatus::Definitive->value(), $user->userStatus->value());

        //再度本登録
        $this->expectException(UserAlreadyDefinitiveException::class);
        $this->expectExceptionMessage(UserAlreadyDefinitiveException::MESSAGE);
        $user->changeDefinitiveRegister($profile);
    }

    public function test_適切なパスワードではない(): never
    {
        $email = UserEmail::of('example@example.com');

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

    public function test_パスワードの変更をハッシュ化されていない場合ハッシュ化して変更する()
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


    public function test_パスワードの変更がハッシュ化されたパスワードで変更できる()
    {
        $hashService = new ConcreteHash();
        $email = UserEmail::of('example@example.com');
        $pass = UserRawPassword::of('AAccddssAA1234!3%ja');
        $user = User::emailAndPasswordTemporaryRegister($email, $pass, $hashService);


        $hashPass = (new ConcreteHash())->hashing(UserRawPassword::of('AAccddssAA1234!3%ja'));


        $user->changePassword($hashPass);

        self::assertSame($hashPass->value(), $user->userPassword->value());
    }

    public function test_永続化からの復帰()
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

    public function test_ユーザ同士で同じIDの比較()
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

    public function test_別のユーザの比較()
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

    public function test_二重でパスワードをハッシュ化しようとすると例外出ることを確認()
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
}

class ConcreteHash implements HashService
{

    public function hashing(StringValueObject $raw): UserHashPassword
    {
        return UserHashPassword::of(hash('sha256', $raw->value()));
    }
}
