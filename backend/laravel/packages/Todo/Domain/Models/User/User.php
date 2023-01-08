<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
use Todo\Domain\Models\Profile\IProfile;
use Todo\Domain\Models\Profile\Profile;
use Todo\Domain\Models\User\Exception\PasswordEncryptionException;
use Todo\Domain\Models\User\Exception\UserAlreadyDefinitiveException;
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
     *
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
     *
     *
     * @throws InvalidUuidException
     * @throws PasswordEncryptionException
     */
    public static function emailAndPasswordTemporaryRegister(
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

    public function unsubscribe(): void
    {
        $this->userStatus = UserStatus::Unsubscribe;
    }

    public function changeStatus(UserStatus $userStatus): void
    {
        $this->userStatus = $userStatus;
    }

    public function changePassword(Password $password, ?HashService $hashService = null): void
    {
        if ($password instanceof UserRawPassword || $hashService !== null) {
            $password = $hashService->hashing($password);
        }
        $this->userPassword = $password;
    }

    /**
     * @param User $domain
     */
    public function equals(Domain $domain): bool
    {
        return $this->userId->equals($domain->userId);
    }

    /**
     *
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

    private function isTemporary(): bool
    {
        return $this->profile !== null;
    }

    /**
     *
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
