<?php

declare(strict_types=1);

namespace Auth\Feature\UseCase;

use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Illuminate\Foundation\Testing\WithFaker;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserOutput;
use SampleHR\Application\UseCases\Register\TemporaryRegisterUserUseCase;
use SampleHR\Domain\Models\User\Exception\UserEmailAlreadyException;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserRawPassword;
use SampleHR\Infrastructure\Repositories\InMemory\InMemoryUserRepository;
use Tests\ConcreteHash;
use Tests\TestCase;

final class RegisterUserUseCaseTest extends TestCase
{
    use WithFaker;

    public function test_正常()
    {
        $password = 'U4s_qtL,';
        $dto = new ConcreteInput($this->faker->safeEmail, $password);

        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertFalse($result->isError());
        self::assertIsObject($result->getResultValue());
        self::assertInstanceOf(RegisterUserOutput::class, $result->getResultValue());
    }

    public function test_メールアドレスの重複()
    {
        $hashService = new ConcreteHash();
        $email = $this->faker->safeEmail;
        $password = 'U4s_qtL,';

        $dto = new ConcreteInput($email, $password);
        $userDomain = User::register(
            UserEmail::of($dto->getEmail()),
            UserRawPassword::of($dto->getPassword()),
            $hashService
        );

        $repository = new InMemoryUserRepository();
        $repository->create($userDomain);

        $this->app->instance(UserRepository::class, $repository);
        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertTrue($result->isError());
        self::assertInstanceOf(UserEmailAlreadyException::class, $result->getException());
    }

    public function test_正しくないメールアドレス()
    {
        $email = 'test@aaaaa';
        $password = 'U4s_qtL,';

        $dto = new ConcreteInput($email, $password);

        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertTrue($result->isError());
        self::assertInstanceOf(InvalidEmailAddressException::class, $result->getException());
    }

    public function test_パスワード強度が足りない()
    {
        $password = 'aaaa';
        $email = $this->faker->safeEmail;

        $dto = new ConcreteInput($email, $password);

        /** @var TemporaryRegisterUserUseCase $useCase */
        $useCase = app(TemporaryRegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertTrue($result->isError());
    }

}

readonly class ConcreteInput implements RegisterUserInput
{

    public function __construct(
        private string $email,
        private string $password,
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
