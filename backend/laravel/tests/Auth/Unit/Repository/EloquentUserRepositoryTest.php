<?php

declare(strict_types=1);

namespace Auth\Unit\Repository;


use Illuminate\Foundation\Testing\WithFaker;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserRawPassword;
use SampleHR\Infrastructure\EloquentModels\EloquentUser;
use SampleHR\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
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

        $userDomain = User::temporaryRegister(
            UserEmail::of($email),
            UserRawPassword::of($password),
            $hashService,
        );

        $repo = new EloquentUserRepository();
        $repo->create($userDomain);

        $dbUser = EloquentUser::where('email', $email)->first();
        self::assertEquals($email, $dbUser->email);
    }
}
