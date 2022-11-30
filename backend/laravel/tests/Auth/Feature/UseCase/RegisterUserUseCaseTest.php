<?php

declare(strict_types=1);

namespace Auth\Feature\UseCase;

use App\Mail\RegisterMail;
use Auth\Adapter\Http\RegisterUser;
use Auth\Adapter\Http\RegisterUserOutput;
use Auth\Application\UseCases\RegisterUserUseCase;
use Auth\Domain\Exceptions\UserEmailAlreadyException;
use Auth\Domain\Models\User\User;
use Auth\Domain\Models\User\UserRepository;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserPassword;
use Auth\Infrastructure\Repositories\InMemory\InMemoryUserRepository;
use Base\DomainSupport\Exception\InvalidEmailAddressException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class RegisterUserUseCaseTest extends TestCase
{
    use WithFaker;

    public function test_正常()
    {
        Mail::fake();

        $password = 'U4s_qtL,';
        $dto = new RegisterUser($this->faker->safeEmail, $password);

        /** @var RegisterUserUseCase $useCase */
        $useCase = app(RegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertFalse($result->isError());

        self::assertIsObject($result->getResultValue());

        self::assertInstanceOf(RegisterUserOutput::class, $result->getResultValue());

        Mail::assertSent(RegisterMail::class, function ($mail) use ($dto) {
           return $mail->hasTo($dto->email);
        });
    }

    public function test_メールアドレスの重複()
    {
        $email = $this->faker->safeEmail;
        $password = 'U4s_qtL,';

        $dto = new RegisterUser($email, $password);
        $userDomain = User::register(UserEmail::of($dto->email), UserPassword::of($dto->password));

        $repository = new InMemoryUserRepository();
        $repository->create($userDomain);

        $this->app->instance(UserRepository::class, $repository);
        /** @var RegisterUserUseCase $useCase */
        $useCase = app(RegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertTrue($result->isError());

        self::assertInstanceOf(UserEmailAlreadyException::class, $result->getException());
    }

    public function test_正しくないメールアドレス()
    {
        $email = 'test@aaaaa';
        $password = 'U4s_qtL,';

        $dto = new RegisterUser($email, $password);

        /** @var RegisterUserUseCase $useCase */
        $useCase = app(RegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertTrue($result->isError());

        self::assertInstanceOf(InvalidEmailAddressException::class, $result->getException());
    }

    public function test_パスワード強度が足りない()
    {
        $password = 'aaaa';
        $email = $this->faker->safeEmail;

        $dto = new RegisterUser($email, $password);

        /** @var RegisterUserUseCase $useCase */
        $useCase = app(RegisterUserUseCase::class);
        $result = $useCase($dto);

        self::assertTrue($result->isError());
    }

}
