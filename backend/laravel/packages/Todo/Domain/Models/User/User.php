<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
use Todo\Domain\Models\Profile\IProfile;
use Todo\Domain\Models\Profile\Profile;
use Todo\Domain\Models\User\Exception\PasswordEncryptionException;
use Todo\Domain\Models\User\Exception\PasswordNotChangeException;
use Todo\Domain\Models\User\Exception\UserAlreadyDefinitiveException;
use Todo\Domain\Models\User\Exception\UserPasswordChangeException;
use Todo\Domain\Models\User\ValueObject\Password;
use Todo\Domain\Models\User\ValueObject\SocialLoginNoPassword;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Models\User\ValueObject\UserHashPassword;
use Todo\Domain\Models\User\ValueObject\UserId;
use Todo\Domain\Models\User\ValueObject\UserRawPassword;
use Todo\Domain\Models\User\ValueObject\UserStatus;

/**
 * @property  UserId                           $userId
 * @property  UserEmail                        $userEmail
 * @property  UserRawPassword|UserHashPassword $userPassword
 * @property  UserStatus                       $userStatus
 * @property  null|Profile                     $profile
 */
final readonly class User implements IUser
{
    use Getter;

    private function __construct(
        private UserId $userId,
        private UserEmail $userEmail,
        private Password $userPassword,
        private UserStatus $userStatus,
        private ?IProfile $profile,
    ) {
    }

    /**
     * ソーシャルログイン　仮登録
     *
     * @throws InvalidUuidException
     */
    public static function socialTemporaryRegister(
        UserEmail $userEmail,
    ): IUser {
        return new User(
            UserId::generate(),
            $userEmail,
            SocialLoginNoPassword::of(),
            UserStatus::Temporary,
            null,
        );
    }

    /**
     *  メールアドレス、パスワード仮登録
     *
     * @throws InvalidUuidException
     * @throws PasswordEncryptionException
     * @throws PasswordNotChangeException
     * @throws UserPasswordChangeException
     */
    public static function emailAndPasswordTemporaryRegister(
        UserEmail $userEmail,
        UserRawPassword $userPassword,
        HashService $hashService,
    ): IUser {
        return (new User(
            UserId::generate(),
            $userEmail,
            $userPassword,
            UserStatus::Temporary,
            null,
        ))->changeHashPassword($hashService);
    }

    /**
     * 永続化から復帰
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
        UserStatus $userStatus,
        ?IProfile $profile,
    ): IUser {
        return new User(
            $userId,
            $userEmail,
            $userHashPassword,
            $userStatus,
            $profile,
        );
    }

    /**
     * 本登録
     *
     * @throws UserAlreadyDefinitiveException
     */
    public function definitiveRegister(IProfile $profile): User
    {
        if ($this->isDefinitive()) {
            throw new UserAlreadyDefinitiveException(UserAlreadyDefinitiveException::MESSAGE);
        }
        return $this->changeAttributes(userStatus: UserStatus::Definitive);
    }


    /**
     * 未登録ユーザ　
     *
     * @throws InvalidUuidException
     */
    public static function anonymous(): IUser
    {
        return new User(
            UserId::generate(),
            UserEmail::of('anonymous@example.com'),
            SocialLoginNoPassword::of(),
            UserStatus::Anonymous,
            null,
        );
    }

    /**
     * 退会
     */
    public function unsubscribe(): User
    {
        return $this->changeAttributes(userStatus: UserStatus::Unsubscribe);
    }

    public function changeStatus(UserStatus $userStatus): User
    {
        return $this->changeAttributes(userStatus: $userStatus);
    }

    /**
     * @throws UserPasswordChangeException
     * @throws PasswordNotChangeException
     */
    public function changePassword(Password $password, ?HashService $hashService = null): User
    {
        if ($this->userPassword instanceof SocialLoginNoPassword) {
            throw new PasswordNotChangeException(PasswordNotChangeException::MESSAGE);
        }

        if ($password instanceof UserRawPassword) {
            if ($hashService === null) {
                throw new UserPasswordChangeException(UserPasswordChangeException::MESSAGE);
            }
            $password = $hashService->hashing($password);
        }

        return $this->changeAttributes(password: $password);
    }

    public function equals(self|Domain $domain): bool
    {
        return $this->userId->equals($domain->userId);
    }

    private function isDefinitive(): bool
    {
        return $this->userStatus === UserStatus::Definitive;
    }

    /**
     * @throws UserPasswordChangeException
     * @throws PasswordEncryptionException
     * @throws PasswordNotChangeException
     */
    private function changeHashPassword(HashService $hashService): User
    {
        if ($this->userPassword instanceof SocialLoginNoPassword) {
            throw new PasswordNotChangeException(PasswordNotChangeException::MESSAGE);
        }

        if ($this->userPassword instanceof UserHashPassword) {
            throw new PasswordEncryptionException(PasswordEncryptionException::MESSAGE);
        }

        $hasPassword = $hashService->hashing($this->userPassword);
        return $this->changePassword($hasPassword);
    }


    private function changeAttributes(
        ?UserId $userId = null,
        ?UserEmail $userEmail = null,
        ?Password $password = null,
        ?UserStatus $userStatus = null,
        ?IProfile $profile = null,
    ): User {
        return new User(
            $userId ?? $this->userId,
            $userEmail ?? $this->userEmail,
            $password ?? $this->userPassword,
            $userStatus ?? $this->userStatus,
            $profile ?? $this->profile,
        );
    }
}
