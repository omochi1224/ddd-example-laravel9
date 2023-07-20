<?php

declare(strict_types=1);

namespace Sample\Application\Tests;


use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserInput;
use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserOutput;
use Sample\Application\UseCases\User\DefinitiveRegisterUserUseCase;
use Sample\Domain\Models\Profile\Profile;
use Sample\Domain\Models\User\Exception\UserNotFoundException;
use Tests\TestCase;

use function PHPUnit\Framework\assertFalse;

final class DefinitiveRegisterUserUseCaseTest extends TestCase
{
    public function test_本登録()
    {
        $domain = $this->dummyUserInRepository();

        /** @var DefinitiveRegisterUserUseCase $useCase */
        $useCase = app(DefinitiveRegisterUserUseCase::class);

        $input = new ConcreteDefinitiveRegisterUserInput(userId: $domain->userId->value());

        $result = $useCase($input);

        assertFalse($result->isError());

        /** @var DefinitiveRegisterUserOutput $resultValue */
        $resultValue = $result->getResultValue();

        //本登録なのでプロフィールが追加されているか確認
        self::assertInstanceOf(Profile::class, $resultValue->user->profile);
    }

    public function test_仮登録ユーザが見つからない()
    {
        /** @var DefinitiveRegisterUserUseCase $useCase */
        $useCase = app(DefinitiveRegisterUserUseCase::class);

        $input = new ConcreteDefinitiveRegisterUserInput();

        $result = $useCase($input);

        self::assertTrue($result->isError());

        self::assertEquals(UserNotFoundException::MESSAGE, $result->getException()->getMessage());
    }
}

readonly class ConcreteDefinitiveRegisterUserInput implements DefinitiveRegisterUserInput
{

    public function __construct(
        private string $userId = '48bc9625-62e9-40b3-b229-9ba371e3304a',
        private array $name = ['lastName' => '田中', 'firstName' => '太郎'],
        private \DateTime $birthday = new \DateTime(),
        private string $image = 'https://example.com/user1.jpg',
        private int $gender = 0,
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return array{lastName: string, firstName: string}
     */
    public function getName(): array
    {
        return $this->name;
    }

    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getGender(): int
    {
        return $this->gender;
    }
}
