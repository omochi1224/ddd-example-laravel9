<?php

declare(strict_types=1);

namespace Todo\Domain\Tests\Service;


use PHPUnit\Framework\TestCase;
use Todo\Domain\Models\User\User;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Services\UserDomainService;
use Todo\Infrastructure\Repositories\InMemory\InMemoryUserRepository;

final class UserServiceTest extends TestCase
{
    public function test_すでに同じメールアドレスで登録済み(): void
    {
        $user = User::socialTemporaryRegister(UserEmail::of('example@example.com'));
        $users = [$user->userId->value() => $user];
        $service = new UserDomainService(new InMemoryUserRepository($users));
        self::assertTrue($service->isUserEmailExists(UserEmail::of('example@example.com')));
    }

    public function test_メールアドレスが存在しない場合(): void
    {
        $user = User::socialTemporaryRegister(UserEmail::of('example123@example.com'));
        $users = [$user->userId->value() => $user];
        $service = new UserDomainService(new InMemoryUserRepository($users));
        self::assertFalse($service->isUserEmailExists(UserEmail::of('example@example.com')));
    }
}
