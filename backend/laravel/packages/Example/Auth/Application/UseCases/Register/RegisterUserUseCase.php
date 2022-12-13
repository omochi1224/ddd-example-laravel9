<?php

declare(strict_types=1);

namespace Auth\Application\UseCases\Register;

use Auth\Application\UseCases\Register\Adapter\RegisterUserInput;
use Auth\Application\UseCases\Register\Adapter\RegisterUserOutput;
use Auth\Domain\Models\User\Exception\UserEmailAlreadyException;
use Auth\Domain\Models\User\HashService;
use Auth\Domain\Models\User\User;
use Auth\Domain\Models\User\UserRepository;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserPassword;
use Auth\Domain\Services\UserDomainService;
use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;

/**
 *
 */
final class RegisterUserUseCase
{
    /**
     * @param Transaction       $transaction
     * @param UserDomainService $userDomainService
     * @param UserRepository    $userRepository
     * @param HashService       $hashService
     */
    public function __construct(
        private readonly Transaction $transaction,
        private readonly UserDomainService $userDomainService,
        private readonly UserRepository $userRepository,
        private readonly HashService $hashService,
    ) {
    }

    /**
     * @param RegisterUserInput $registerUserDto
     *
     * @return UseCaseResult
     */
    public function __invoke(RegisterUserInput $registerUserDto): UseCaseResult
    {
        $this->transaction->begin();
        try {
            $user = User::register(
                UserEmail::of($registerUserDto->getEmail()),
                UserPassword::of($registerUserDto->getPassword()),
                $this->hashService,
            );

            //email存在チェック
            if ($this->userDomainService->isEmailAlready($user->userEmail)) {
                return UseCaseResult::fail(new UserEmailAlreadyException());
            }

            //ユーザを永続化
            $user = $this->userRepository->create($user);

            $this->transaction->commit();
        } catch (DomainException $exception) {
            $this->transaction->rollback();
            return UseCaseResult::fail($exception);
        }

        return UseCaseResult::success(new RegisterUserOutput($user));
    }
}
