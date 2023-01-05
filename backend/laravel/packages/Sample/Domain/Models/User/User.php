<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
use Sample\Domain\Models\Profile\IProfile;
use Sample\Domain\Models\Profile\Profile;
use Sample\Domain\Models\User\Exception\PasswordEncryptionException;
use Sample\Domain\Models\User\Exception\UserAlreadyDefinitiveException;
use Sample\Domain\Models\User\ValueObject\Password;
use Sample\Domain\Models\User\ValueObject\SocialLoginNoPassword;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserHashPassword;
use Sample\Domain\Models\User\ValueObject\UserId;
use Sample\Domain\Models\User\ValueObject\UserRawPassword;
use Sample\Domain\Models\User\ValueObject\UserStatus;

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

    /**
     * @param UserId        $userId
     * @param UserEmail     $userEmail
     * @param Password      $userPassword
     * @param UserStatus    $userStatus
     * @param IProfile|null $profile
     */
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
     * @param UserEmail $userEmail
     *
     * @return User
     *
     * @throws InvalidUuidException
     */
    public static function socialTemporaryRegister(
        UserEmail $userEmail,
    ): User {
        return new User(
            UserId::generate(),
            $userEmail,
            SocialLoginNoPassword::of(),
            UserStatus::Temporary,
            null,
        );
    }

    /**
     *  仮登録
     *
     * @param UserEmail       $userEmail
     * @param UserRawPassword $userPassword
     * @param HashService     $hashService
     *
     * @return User
     *
     * @throws InvalidUuidException
     * @throws PasswordEncryptionException
     */
    public static function temporaryRegister(
        UserEmail $userEmail,
        UserRawPassword $userPassword,
        HashService $hashService,
    ): User {
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
     * @param UserId           $userId
     * @param UserEmail        $userEmail
     * @param UserHashPassword $userHashPassword
     * @param UserStatus       $userStatus
     * @param IProfile|null    $profile
     *
     * @return self
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
        UserStatus $userStatus,
        ?IProfile $profile,
    ): User {
        return new User(
            $userId,
            $userEmail,
            $userHashPassword,
            $userStatus,
            $profile,
        );
    }

    /**
     * @return void
     */
    public function unsubscribe(): void
    {
        $this->userStatus = UserStatus::Unsubscribe;
    }

    /**
     * @param UserStatus $userStatus
     *
     * @return void
     */
    public function changeStatus(UserStatus $userStatus): void
    {
        $this->userStatus = $userStatus;
    }

    /**
     * @param Password         $password
     * @param HashService|null $hashService
     *
     * @return void
     */
    public function changePassword(Password $password, ?HashService $hashService = null): void
    {
        if ($password instanceof UserRawPassword || $hashService !== null) {
            $password = $hashService->hashing($password);
        }

        $this->userPassword = $password;
    }

    /**
     * @param User $domain
     *
     * @return bool
     */
    public function equals(Domain $domain): bool
    {
        return $this->userId->equals($domain->userId);
    }

    /**
     * @param IProfile $profile
     *
     * @return void
     *
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

    /**
     * @return bool
     */
    private function isTemporary(): bool
    {
        return $this->profile !== null;
    }

    /**
     * @param HashService $hashService
     *
     * @return void
     *
     * @throws PasswordEncryptionException
     */
    private function changeHashPassword(HashService $hashService): void
    {
        if ($this->userPassword instanceof UserHashPassword) {
            throw new PasswordEncryptionException(PasswordEncryptionException::MESSAGE);
        }

        $password = $this->userPassword->value();
        $this->userPassword = $hashService->hashing(UserRawPassword::of($password));
    }
}
