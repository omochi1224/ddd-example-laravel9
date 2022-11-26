<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User;

use Auth\Domain\Models\Profile\Profile;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Auth\Domain\Models\User\ValueObject\UserId;
use Auth\Domain\Models\User\ValueObject\UserPassword;
use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Domain\Getter;
use Base\DomainSupport\Exception\InvalidUuidException;

/**
 * @property-read  UserId                        $userId
 * @property-read  UserEmail                     $userEmail
 * @property-read  UserPassword|UserHashPassword $userPassword
 * @property-read  Profile|null                  $profile
 */
final class User implements Domain
{
    use Getter;

    /**
     * @param UserId                        $userId
     * @param UserEmail                     $userEmail
     * @param UserPassword|UserHashPassword $userPassword
     * @param Profile|null                  $profile
     */
    private function __construct(
        private readonly UserId $userId,
        private UserEmail $userEmail,
        private UserPassword|UserHashPassword $userPassword,
        private ?Profile $profile,
    ) {
    }

    /**
     * @param UserEmail    $userEmail
     * @param UserPassword $userPassword
     *
     * @return self
     *
     * @throws InvalidUuidException
     */
    public static function register(
        UserEmail $userEmail,
        UserPassword $userPassword
    ): self {
        return new User(
            UserId::generate(),
            $userEmail,
            $userPassword,
            null,
        );
    }

    /**
     * @param UserId           $userId
     * @param UserEmail        $userEmail
     * @param UserHashPassword $userHashPassword
     * @param Profile|null     $profile
     *
     * @return self
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
        ?Profile $profile,
    ): User {
        return new User(
            $userId,
            $userEmail,
            $userHashPassword,
            $profile,
        );
    }

    /**
     * @param UserHashPassword $userHashPassword
     *
     * @return void
     */
    public function changeHashPassword(UserHashPassword $userHashPassword): void
    {
        $this->userPassword = $userHashPassword;
    }

    /**
     * @param UserEmail $userEmail
     *
     * @return void
     */
    public function changeEmail(UserEmail $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @param Profile $profile
     *
     * @return void
     */
    public function changeProfile(Profile $profile): void
    {
        $this->profile = $profile;
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
}
