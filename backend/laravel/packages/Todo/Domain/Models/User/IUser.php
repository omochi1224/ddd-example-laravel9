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
 * @property  UserId                           $userId
 * @property  UserEmail                        $userEmail
 * @property  UserRawPassword|UserHashPassword $userPassword
 * @property  UserStatus                       $userStatus
 * @property  null|Profile                     $profile
 * @psalm-seal-properties
 */
interface IUser extends Domain
{
    /**
     * 仮登録
     */
    public static function emailAndPasswordTemporaryRegister(
        UserEmail $userEmail,
        UserRawPassword $userPassword,
        HashService $hashService,
    ): IUser;

    /**
     * ソーシャルログイン　仮登録
     *
     * @throws InvalidUuidException
     */
    public static function socialTemporaryRegister(
        UserEmail $userEmail,
    ): IUser;

    /**
     * 仮登録から本登録に変更
     */
    public function definitiveRegister(Profile $profile): IUser;

    /**
     * 未登録ユーザ　
     */
    public static function anonymous(): IUser;

    /**
     * 退会
     */
    public function unsubscribe(): IUser;

    /**
     * DBからの復帰
     */
    public static function restoreFromDB(
        UserId $userId,
        UserEmail $userEmail,
        UserHashPassword $userHashPassword,
        UserStatus $userStatus,
        ?IProfile $profile,
    ): IUser;

    /**
     * パスワードの変更
     */
    public function changePassword(
        UserHashPassword|UserRawPassword $password,
        ?HashService $hashService = null
    ): IUser;

    public function changeStatus(UserStatus $userStatus): IUser;
}
