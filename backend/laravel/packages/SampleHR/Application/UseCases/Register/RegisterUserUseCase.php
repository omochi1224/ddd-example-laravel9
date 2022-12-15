<?php

declare(strict_types=1);

namespace SampleHR\Application\UseCases\Register;

use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;
use SampleHR\Domain\Models\User\Exception\UserEmailAlreadyException;
use SampleHR\Domain\Models\User\HashService;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserPassword;
use SampleHR\Domain\Services\UserDomainService;

/**
 *
 */
readonly final class RegisterUserUseCase
{
    /**
     * @param Transaction       $transaction
     * @param UserDomainService $userDomainService
     * @param UserRepository    $userRepository
     * @param HashService       $hashService
     */
    final public function __construct(
        private Transaction $transaction,
        private UserDomainService $userDomainService,
        private UserRepository $userRepository,
        private HashService $hashService,
    ) {
    }

    /**
     * @param RegisterUserInput $registerUserDto
     *
     * @return UseCaseResult
     */
    final public function __invoke(RegisterUserInput $registerUserDto): UseCaseResult
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

        return UseCaseResult::success($user);
    }
}
