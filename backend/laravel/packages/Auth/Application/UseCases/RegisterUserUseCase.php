<?php

declare(strict_types=1);

namespace Auth\Application\UseCases;

use Auth\Application\Dtos\RegisterUserDto;
use Auth\Domain\Exceptions\UserEmailAlreadyException;
use Auth\Domain\Models\User\HashService;
use Auth\Domain\Models\User\User;
use Auth\Domain\Models\User\UserRegisterNotifyMail;
use Auth\Domain\Models\User\UserRegisterNotifyMailRepository;
use Auth\Domain\Models\User\UserRepository;
use Auth\Domain\Models\User\ValueObject\AccessToken;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Auth\Domain\Models\User\ValueObject\UserPassword;
use Auth\Domain\Services\UserDomainService;
use Base\ExceptionSupport\DomainException;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use Common\Domain\Models\Email\EmailSender;

/**
 *
 */
final class RegisterUserUseCase
{
    /**
     * @param Transaction                      $transaction
     * @param UserDomainService                $userDomainService
     * @param UserRepository                   $userRepository
     * @param EmailSender                      $emailSender
     * @param HashService                      $hashService
     * @param UserRegisterNotifyMailRepository $userRegisterNotifyMailRepository
     */
    public function __construct(
        private readonly Transaction $transaction,
        private readonly UserDomainService $userDomainService,
        private readonly UserRepository $userRepository,
        private readonly EmailSender $emailSender,
        private readonly HashService $hashService,
        private readonly UserRegisterNotifyMailRepository $userRegisterNotifyMailRepository
    ) {
    }

    /**
     * @param RegisterUserDto $registerUserDto
     *
     * @return UseCaseResult
     */
    public function __invoke(RegisterUserDto $registerUserDto): UseCaseResult
    {
        return $this->transaction->scope(function () use ($registerUserDto): UseCaseResult {
            try {
                $user = User::register(
                    UserEmail::of($registerUserDto->email),
                    UserPassword::of($registerUserDto->password)
                );

                //email存在チェック
                if ($this->userDomainService->isEmailAlready($user->userEmail)) {
                    return UseCaseResult::fail(new UserEmailAlreadyException());
                }

                //パスワードをハッシュ化
                $hashPassword = UserHashPassword::of($this->hashService->hashing($user->userPassword)->value());
                $user->changeHashPassword($hashPassword);

                //メール送信
                $email = UserRegisterNotifyMail::registerNotificationEmail($user->userEmail);
                $this->emailSender->send($email);

                //トークンをハッシュ化
                $hashToken = AccessToken::of($this->hashService->hashing($email->accessToken)->value());
                $email->changeAccessToken($hashToken);

                //ユーザを永続化
                $user = $this->userRepository->create($user);

                //メールを保存
                $this->userRegisterNotifyMailRepository->create($email);
            } catch (DomainException $exception) {
                return UseCaseResult::fail($exception);
            }

            return UseCaseResult::success($user);
        });
    }
}
