<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;
use SampleHR\Domain\Models\User\Exception\PasswordEncryptionException;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserHashPassword;
use SampleHR\Domain\Models\User\ValueObject\UserId;
use SampleHR\Domain\Models\User\ValueObject\UserPassword;

/**
 * @property-read  UserId                        $userId
 * @property-read  UserEmail                     $userEmail
 * @property-read  UserPassword|UserHashPassword $userPassword
 */
final class User implements Domain
{
    use Getter;

    /**
     * @param UserId                        $userId
     * @param UserEmail                     $userEmail
     * @param UserPassword|UserHashPassword $userPassword
     */
    private function __construct(
        private readonly UserId $userId,
        private readonly UserEmail $userEmail,
        private UserPassword|UserHashPassword $userPassword,
    ) {
    }

    /**
     * @param UserEmail    $userEmail
     * @param UserPassword $userPassword
     * @param HashService  $hashService
     *
     * @return self
     *
     * @throws InvalidUuidException
     * @throws PasswordEncryptionException
     */
    public static function register(
        UserEmail $userEmail,
        UserPassword $userPassword,
        HashService $hashService,
    ): self {
        $user = new User(
            UserId::generate(),
            $userEmail,
            $userPassword,
        );

        $user->changeHashPassword($hashService);

        return $user;
    }

    /**
     * @param UserId           $userId
     * @param UserEmail        $userEmail
     * @param UserHashPassword $userHashPassword
     *
     * @return self
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
    ): User {
        return new User(
            $userId,
            $userEmail,
            $userHashPassword,
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
        $this->userPassword = $hashService->hashing(UserPassword::of($password));
    }
}
