<?php

declare(strict_types=1);

namespace Auth\Unit\Domain;


use Auth\Domain\Models\User\UserRegisterNotifyMail;
use Base\DomainSupport\ValueObject\EmailValueObject;
use Common\Domain\Models\Email\ValueObject\EmailTo;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class UserRegisterNotifyMailTest extends TestCase
{
    use WithFaker;

    public function test_ユーザ登録時のメールドメイン生成テスト()
    {
        $to = EmailTo::of($this->faker->email);
        $email = UserRegisterNotifyMail::registerNotificationEmail($to);
        self::assertEquals($to->value(), $email->emailTo->value());
    }

    public function test_ユーザメールアドレスからユーザ登録メールを生成()
    {
        $to = ConcreteEmailValueObject::of($this->faker->email);
        $email = UserRegisterNotifyMail::registerNotificationEmail($to);
        self::assertEquals($to->value(), $email->emailTo->value());
    }
}

class ConcreteEmailValueObject extends EmailValueObject{

}
