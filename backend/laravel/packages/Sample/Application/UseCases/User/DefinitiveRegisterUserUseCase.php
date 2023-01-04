<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User;

use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserInput;
use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserOutput;
use Sample\Domain\Models\Profile\ProfileFactory;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserId;
use Sample\Domain\Services\UserDomainService;

/**
 *
 */
final readonly class DefinitiveRegisterUserUseCase
{
    /**
     * @param Transaction       $transaction
     * @param UserRepository    $userRepository
     * @param UserDomainService $userDomainService
     */
    public function __construct(
        private Transaction $transaction,
        private UserRepository $userRepository,
        private UserDomainService $userDomainService,
    ) {
    }

    /**
     * @param DefinitiveRegisterUserInput $input
     *
     * @return UseCaseResult
     */
    public function __invoke(DefinitiveRegisterUserInput $input): UseCaseResult
    {
        try {
            $this->transaction->begin();
            $user = $this->userDomainService
                ->isIdAlready(UserId::of($input->getUserId()));

            //Profile生成
            $profile = ProfileFactory::definitive($input);

            //本登録へ変更
            $user->changeDefinitiveRegister($profile);

            //永続化
            $this->userRepository->update($user);

            $output = new DefinitiveRegisterUserOutput($user);

            $this->transaction->commit();

            return UseCaseResult::success($output);
        } catch (DomainException $domainException) {
            $this->transaction->rollback();
            return UseCaseResult::fail($domainException);
        }
    }
}
