<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User;

use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserInput;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserOutput;
use Sample\Domain\Models\User\Exception\UserEmailAlreadyException;
use Sample\Domain\Models\User\HashService;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRegisterNotify;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserRawPassword;
use Sample\Domain\Services\UserDomainService;

/**
 *
 */
final readonly class TemporaryRegisterUserUseCase
{
    final public function __construct(
        private Transaction $transaction,
        private UserDomainService $userDomainService,
        private UserRepository $userRepository,
        private HashService $hashService,
    ) {
    }

    /**
     * @return UseCaseResult
     */
    public function __invoke(TemporaryRegisterUserInput $registerUserDto): UseCaseResult
    {
        try {
            $user = User::temporaryRegister(
                UserEmail::of($registerUserDto->getEmail()),
                UserRawPassword::of($registerUserDto->getPassword()),
                $this->hashService,
            );

            //email存在チェック
            if ($this->userDomainService->isEmailAlready($user)) {
                throw new UserEmailAlreadyException(UserEmailAlreadyException::MESSAGE);
            }

            //通知
            $notify = UserRegisterNotify::of($user);

            return $this->transaction->scope(function () use ($user, $notify) {
                $output = new TemporaryRegisterUserOutput($user, $notify);
                $this->userRepository->create($user);
                return UseCaseResult::success($output);
            });
        } catch (DomainException $exception) {
            return UseCaseResult::fail($exception);
        }

    }
}
