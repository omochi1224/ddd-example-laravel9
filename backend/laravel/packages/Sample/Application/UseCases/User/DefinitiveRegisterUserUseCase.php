<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User;

use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use Illuminate\Support\Facades\DB;
use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserInput;
use Sample\Application\UseCases\User\Adapter\DefinitiveRegisterUserOutput;
use Sample\Domain\Models\Profile\ProfileFactory;
use Sample\Domain\Models\User\Exception\UserNotFoundException;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserId;
use Sample\Domain\Services\UserDomainService;

final readonly class DefinitiveRegisterUserUseCase
{
    public function __construct(
        private Transaction $transaction,
        private UserRepository $userRepository,
        private UserDomainService $userDomainService,
    ) {
    }

    /**
     * @return UseCaseResult
     */
    public function __invoke(DefinitiveRegisterUserInput $input): UseCaseResult
    {

        try {
            $userId = UserId::of($input->getUserId());

            if (!$this->userDomainService->doesUserIdExist($userId)) {
                throw new UserNotFoundException(UserNotFoundException::MESSAGE);
            }

            $user = $this->userRepository->getByUserId($userId);

            //Profile生成
            $profile = ProfileFactory::definitive($input);

            //本登録へ変更
            $user->changeDefinitiveRegister($profile);

            return $this->transaction->scope(function () use ($user): UseCaseResult {
                $this->userRepository->update($user);
                return UseCaseResult::success(new DefinitiveRegisterUserOutput($user));
            });

        } catch (DomainException $domainException) {
            return UseCaseResult::fail($domainException);
        }
    }
}
