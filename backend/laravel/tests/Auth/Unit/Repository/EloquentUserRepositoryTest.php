<?php

declare(strict_types=1);

namespace Auth\Unit\Repository;


use Auth\Domain\Models\User\User;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserPassword;
use Auth\Infrastructure\EloquentModels\EloquentUser;
use Auth\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ConcreteHash;
use Tests\TestCase;

final class EloquentUserRepositoryTest extends TestCase
{
    use WithFaker;

    public function test_作成()
    {
        $hashService = new ConcreteHash();
        $password = 'U4s_qtL,';
        $email = $this->faker->safeEmail;

        $userDomain = User::register(
            UserEmail::of($email),
            UserPassword::of($password),
            $hashService,
        );

        $repo = new EloquentUserRepository();
        $repo->create($userDomain);

        $dbUser = EloquentUser::where('email', $email)->first();
        self::assertEquals($email, $dbUser->email);
    }
}
