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
use Todo\Domain\Models\User\Exception\PasswordStrengthException;
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
 * @property-read  UserId                           $userId
 * @property-read  UserEmail                        $userEmail
 * @property-read  UserRawPassword|UserHashPassword $userPassword
 * @property-read  UserStatus                       $userStatus
 * @property-read  ?Profile                         $profile
 */
final class User implements IUser
{
    use Getter;

    private function __construct(
        private readonly UserId $userId,
        private readonly UserEmail $userEmail,
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
        $user = new User(
            UserId::generate(),
            $userEmail,
            $userPassword,
            UserStatus::Temporary,
            null,
        );

        $user->changeHashPassword($hashService);

        return $user;
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
     * 退会
     */
    public function unsubscribe(): void
    {
        $this->userStatus = UserStatus::Unsubscribe;
    }

    public function changeStatus(UserStatus $userStatus): void
    {
        $this->userStatus = $userStatus;
    }

    /**
     * @throws UserPasswordChangeException
     * @throws PasswordNotChangeException
     */
    public function changePassword(Password $password, ?HashService $hashService = null): void
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
        $this->userPassword = $password;
    }

    public function equals(self|Domain $domain): bool
    {
        return $this->userId->equals($domain->userId);
    }

    /**
     * @throws UserAlreadyDefinitiveException
     */
    public function changeDefinitiveRegister(IProfile $profile): void
    {
        if ($this->isTemporary()) {
            throw new UserAlreadyDefinitiveException(UserAlreadyDefinitiveException::MESSAGE);
        }
        $this->userStatus = UserStatus::Definitive;
        $this->profile = $profile;
    }

    private function isTemporary(): bool
    {
        return $this->profile !== null;
    }

    /**
     * @throws UserPasswordChangeException
     * @throws PasswordEncryptionException
     * @throws PasswordNotChangeException
     */
    private function changeHashPassword(HashService $hashService): void
    {
        if ($this->userPassword instanceof SocialLoginNoPassword) {
            throw new PasswordNotChangeException(PasswordNotChangeException::MESSAGE);
        }

        if ($this->userPassword instanceof UserHashPassword) {
            throw new PasswordEncryptionException(PasswordEncryptionException::MESSAGE);
        }

        try {
            $this->userPassword = $hashService->hashing($this->userPassword);
        } catch (PasswordStrengthException $e) {
            throw new UserPasswordChangeException($e->getMessage());
        }
    }

    /**
     * 未登録ユーザ　
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
}
