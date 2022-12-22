<?php

declare(strict_types=1);

namespace SampleHR\Application\UseCases\Register;

use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserOutput;
use SampleHR\Domain\Models\User\Exception\UserEmailAlreadyException;
use SampleHR\Domain\Models\User\HashService;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\UserRegisterNotify;
use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserRawPassword;
use SampleHR\Domain\Services\UserDomainService;

/**
 *
 */
final readonly class TemporaryRegisterUserUseCase
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
     * @return UseCaseResult<RegisterUserOutput>
     */
    final public function __invoke(RegisterUserInput $registerUserDto): UseCaseResult
    {
        $this->transaction->begin();
        try {
            $user = User::temporaryRegister(
                UserEmail::of($registerUserDto->getEmail()),
                UserRawPassword::of($registerUserDto->getPassword()),
                $this->hashService,
            );

            //email存在チェック
            if ($this->userDomainService->isEmailAlready($user->userEmail)) {
                return UseCaseResult::fail(new UserEmailAlreadyException());
            }

            //通知
            $notify = UserRegisterNotify::of($user);

            $output = new RegisterUserOutput($user, $notify);

            //ユーザを永続化
            $this->userRepository->create($user);

            $this->transaction->commit();
        } catch (DomainException $exception) {
            $this->transaction->rollback();
            return UseCaseResult::fail($exception);
        }

        return UseCaseResult::success($output);
    }
}
