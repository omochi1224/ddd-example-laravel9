<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserInput;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Infrastructure\Repositories\InMemory\InMemoryUserRepository;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function dummyUserInRepository()
    {
        $inputAdapter = new ConcreteTemporaryRegisterUserInput();
        $email = UserEmail::of($inputAdapter->getEmail());
        $domain = User::socialTemporaryRegister($email);
        $dummyRepository = new InMemoryUserRepository();
        $dummyRepository->create($domain);
        $this->app->instance(UserRepository::class, $dummyRepository);
        return $domain;
    }
}

readonly class ConcreteTemporaryRegisterUserInput implements TemporaryRegisterUserInput
{

    public function __construct(
        private string $email = 'example@example.com',
        private string $password = 'Password!1234@pAssword',
    ) {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}

