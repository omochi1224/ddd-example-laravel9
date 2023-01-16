<?php

declare(strict_types=1);

namespace Todo\Application\UseCases\TemporarySNSRegister;

use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use Todo\Domain\Models\User\Exception\UserEmailAlreadyException;
use Todo\Domain\Models\User\User;
use Todo\Domain\Models\User\UserRepository;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Services\UserDomainService;

final readonly class TemporarySNSRegisterUseCase
{
    public function __construct(
        private Transaction $transaction,
        private UserDomainService $userDomainService,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(TemporarySNSRegisterInputAdapter $registerAdapter): UseCaseResult
    {
        try {
            $this->transaction->begin();

            $user = User::socialTemporaryRegister(UserEmail::of($registerAdapter->getEmail()));

            if ($this->userDomainService->isUserEmailExists($user->userEmail)) {
                throw new UserEmailAlreadyException(UserEmailAlreadyException::MESSAGE);
            }

            $this->userRepository->create($user);

            $output = new TemporarySNSRegisterOutputAdapter($user);

            $this->transaction->commit();

            return UseCaseResult::success($output);
        } catch (DomainException $exception) {
            $this->transaction->rollback();
            return UseCaseResult::fail($exception);
        }
    }
}
