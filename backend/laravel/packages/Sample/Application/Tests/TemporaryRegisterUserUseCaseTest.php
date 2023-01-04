<?php

declare(strict_types=1);

namespace Sample\Application\Tests;


use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserOutput;
use Sample\Application\UseCases\User\TemporaryRegisterUserUseCase;
use Sample\Domain\Models\User\Exception\PasswordStrengthException;
use Sample\Domain\Models\User\Exception\UserEmailAlreadyException;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Infrastructure\Repositories\InMemory\InMemoryUserRepository;
use Tests\ConcreteTemporaryRegisterUserInput;
use Tests\TestCase;


final class TemporaryRegisterUserUseCaseTest extends TestCase
{

    public function test_仮登録()
    {
        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUsecase::class);

        $inputAdapter = new ConcreteTemporaryRegisterUserInput();

        $result = $useCase($inputAdapter);

        /** @var TemporaryRegisterUserOutput $outputAdapter */
        $outputAdapter = $result->getResultValue();
        self::assertSame($inputAdapter->getEmail(), $outputAdapter->user->userEmail->value());
    }


    public function test_メール重複()
    {
        $inputAdapter = new ConcreteTemporaryRegisterUserInput();
        $email = UserEmail::of($inputAdapter->getEmail());
        $domain = User::socialTemporaryRegister($email);
        $dummyRepository = new InMemoryUserRepository();
        $dummyRepository->create($domain);
        $this->app->instance(UserRepository::class, $dummyRepository);


        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUsecase::class);

        $inputAdapter = new ConcreteTemporaryRegisterUserInput();

        $result = $useCase($inputAdapter);

        self::assertTrue($result->isError());

        self::assertInstanceOf(UserEmailAlreadyException::class, $result->getException());
        self::assertSame(UserEmailAlreadyException::MESSAGE, $result->getException()->getMessage());
    }

    public function test_不正なメールアドレス()
    {
        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUsecase::class);

        $inputAdapter = new ConcreteTemporaryRegisterUserInput(email: 'test');

        $result = $useCase($inputAdapter);

        self::assertTrue($result->isError());

        self::assertInstanceOf(InvalidEmailAddressException::class, $result->getException());
        self::assertSame(InvalidEmailAddressException::MESSAGE, $result->getException()->getMessage());
    }

    public function test_不正なパスワード()
    {
        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUsecase::class);

        $inputAdapter = new ConcreteTemporaryRegisterUserInput(password: 'test');

        $result = $useCase($inputAdapter);

        self::assertTrue($result->isError());

        self::assertInstanceOf(PasswordStrengthException::class, $result->getException());
        self::assertSame(PasswordStrengthException::MESSAGE, $result->getException()->getMessage());
    }

    public function test_メールアドレス重複()
    {
        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUsecase::class);

        $inputAdapter = new ConcreteTemporaryRegisterUserInput(password: 'test');

        $result = $useCase($inputAdapter);

        self::assertTrue($result->isError());

        self::assertInstanceOf(PasswordStrengthException::class, $result->getException());
        self::assertSame(PasswordStrengthException::MESSAGE, $result->getException()->getMessage());
    }


}

