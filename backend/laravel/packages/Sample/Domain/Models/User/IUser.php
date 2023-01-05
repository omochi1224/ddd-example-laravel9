<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User;

use Base\DomainSupport\Domain\Domain;
use Base\DomainSupport\Exception\InvalidUuidException;
use Sample\Domain\Models\Profile\IProfile;
use Sample\Domain\Models\Profile\Profile;
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
 *
 * @method void changeTemporary()                   仮登録
 * @method void changeDefinitive()                  本番登録
 * @method void changeUnsubscribe()                 退会
 * @method void changeBan()                         アカウントBAN
 */
interface IUser extends Domain
{
    /**
     * 仮登録
     *
     * @param UserEmail       $userEmail
     * @param UserRawPassword $userPassword
     * @param HashService     $hashService
     *
     * @return User
     */
    public static function temporaryRegister(
        UserEmail $userEmail,
        UserRawPassword $userPassword,
        HashService $hashService,
    ): User;

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
    ): User;

    /**
     * 仮登録から本登録に変更
     *
     * @param Profile $profile
     *
     * @return void
     */
    public function changeDefinitiveRegister(Profile $profile): void;

    /**
     * 退会
     *
     * @return void
     */
    public function unsubscribe(): void;

    /**
     * DBからの復帰
     *
     * @param UserId           $userId
     * @param UserEmail        $userEmail
     * @param UserHashPassword $userHashPassword
     * @param UserStatus       $userStatus
     * @param IProfile|null    $profile
     *
     * @return User
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
     * @param UserHashPassword|UserRawPassword $password
     * @param HashService|null                 $hashService
     *
     * @return void
     */
    public function changePassword(
        UserHashPassword|UserRawPassword $password,
        ?HashService $hashService = null
    ): void;

    /**
     * @param UserStatus $userStatus
     *
     * @return void
     */
    public function changeStatus(UserStatus $userStatus): void;
}
