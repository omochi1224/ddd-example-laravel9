<?php

declare(strict_types=1);

namespace Sample\Domain\Tests\Model;


use PHPUnit\Framework\TestCase;
use Sample\Domain\Models\Administrator\Administrator;
use Sample\Domain\Models\User\IUser;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserStatus;

final class AdministratorTest extends TestCase
{
    public function test_管理者がユーザアカウントBAN()
    {
        $user = $this->fakeUser();
        $admin = Administrator::of($user);

        $accountBanUser = $this->fakeUser();
        $admin->accountBan($accountBanUser);

        self::assertSame(UserStatus::Ban, $accountBanUser->userStatus);
    }

    public function test_管理者がユーザを退会させる()
    {
        $unsubscribeUser = $this->fakeUser();

        $user = $this->fakeUser();

        $admin = Administrator::of($user);
        $admin->unsubscribe($unsubscribeUser);

        self::assertSame(UserStatus::Unsubscribe, $unsubscribeUser->userStatus);
    }

    private function fakeUser(string $email = 'fake@example.com'): IUser
    {
        $email = UserEmail::of($email);
        return User::socialTemporaryRegister($email);
    }
}
