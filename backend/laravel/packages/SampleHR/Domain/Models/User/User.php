<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
use SampleHR\Domain\Models\Profile\Profile;
use SampleHR\Domain\Models\User\Exception\PasswordEncryptionException;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserHashPassword;
use SampleHR\Domain\Models\User\ValueObject\UserId;
use SampleHR\Domain\Models\User\ValueObject\UserRawPassword;

/**
 * @property-read  UserId                           $userId
 * @property-read  UserEmail                        $userEmail
 * @property-read  UserRawPassword|UserHashPassword $userPassword
 * @property-read  Profile                          $profile
 */
final class User implements Domain
{
    use Getter;

    /**
     * @param UserId                           $userId
     * @param UserEmail                        $userEmail
     * @param UserRawPassword|UserHashPassword $userPassword
     * @param Profile                          $profile
     */
    private function __construct(
        private readonly UserId $userId,
        private readonly UserEmail $userEmail,
        private UserRawPassword|UserHashPassword $userPassword,
        private readonly Profile $profile,
    ) {
    }

    /**
     *  仮登録
     *
     * @param UserEmail       $userEmail
     * @param UserRawPassword $userPassword
     * @param HashService     $hashService
     *
     * @return User
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
            Profile::temporaryRegister(),
        );

        $user->changeHashPassword($hashService);

        return $user;
    }

    /**
     * 本登録処理
     *
     * @param Profile $profile
     *
     * @return void
     */
    public function changeDefinitive(Profile $profile): void
    {
        $this->profile->changeDefinitive($profile);
    }


    /**
     * @param UserId           $userId
     * @param UserEmail        $userEmail
     * @param UserHashPassword $userHashPassword
     * @param Profile          $profile
     *
     * @return self
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
        Profile $profile,
    ): User {
        return new User(
            $userId,
            $userEmail,
            $userHashPassword,
            $profile,
        );
    }


    /**
     * @param UserHashPassword $password
     *
     * @return void
     */
    public function changePassword(UserHashPassword $password): void
    {
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
