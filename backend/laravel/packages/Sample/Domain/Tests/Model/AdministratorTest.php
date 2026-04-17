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
        $admin->accountBan();

        self::assertSame(UserStatus::Ban, $user->userStatus);
    }

    public function test_管理者がユーザを退会させる()
    {
        $user = $this->fakeUser();
        $admin = Administrator::of($user);
        $admin->unsubscribe();

        self::assertSame(UserStatus::Unsubscribe, $user->userStatus);
    }

    public function test_別ユーザのステータスには影響しない()
    {
        $targetUser = $this->fakeUser('target@example.com');
        $otherUser = $this->fakeUser('other@example.com');

        $admin = Administrator::of($targetUser);
        $admin->accountBan();

        self::assertSame(UserStatus::Ban, $targetUser->userStatus);
        self::assertSame(UserStatus::Temporary, $otherUser->userStatus);
    }

    private function fakeUser(string $email = 'fake@example.com'): IUser
    {
        return User::socialTemporaryRegister(UserEmail::of($email));
    }
}
