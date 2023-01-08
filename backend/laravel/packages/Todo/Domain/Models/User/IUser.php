<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Exception\InvalidUuidException;
use Todo\Domain\Models\Profile\IProfile;
use Todo\Domain\Models\Profile\Profile;
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
interface IUser extends Domain
{
    /**
     * 仮登録
     *
     *
     */
    public static function emailAndPasswordTemporaryRegister(
        UserEmail $userEmail,
        UserRawPassword $userPassword,
        HashService $hashService,
    ): User;

    /**
     * ソーシャルログイン　仮登録
     *
     *
     *
     * @throws InvalidUuidException
     */
    public static function socialTemporaryRegister(
        UserEmail $userEmail,
    ): User;

    /**
     * 仮登録から本登録に変更
     *
     *
     */
    public function changeDefinitiveRegister(Profile $profile): void;

    /**
     * 退会
     */
    public function unsubscribe(): void;

    /**
     * DBからの復帰
     *
     *
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
        UserStatus $userStatus,
        ?IProfile $profile,
    ): User;

    /**
     * パスワードの変更
     *
     *
     */
    public function changePassword(
        UserHashPassword|UserRawPassword $password,
        ?HashService $hashService = null
    ): void;

    public function changeStatus(UserStatus $userStatus): void;
}
