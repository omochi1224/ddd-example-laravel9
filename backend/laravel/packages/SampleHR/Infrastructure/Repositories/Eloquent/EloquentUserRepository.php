<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Repositories\Eloquent;

use Base\DomainSupport\Domain\DomainToArray;
use Base\DomainSupport\Exception\InvalidUuidException;
use SampleHR\Domain\Models\Profile\Exception\ProfileInvalidImageUrlException;
use SampleHR\Domain\Models\Profile\Profile;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileBirthDay;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileFirstName;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileGender;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileId;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileImage;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileLastName;
use SampleHR\Domain\Models\Profile\ValueObject\ProfileName;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;
use SampleHR\Domain\Models\User\ValueObject\UserHashPassword;
use SampleHR\Domain\Models\User\ValueObject\UserId;
use SampleHR\Infrastructure\EloquentModels\EloquentUser;

/**
 *
 */
final class EloquentUserRepository implements UserRepository
{
    use DomainToArray;

    /**
     * @param User $user
     *
     * @return void
     */
    public function create(User $user): void
    {
        dd($this->toArray($user));
        $model = new EloquentUser();
        $model
            ->fill($this->toArray($user))
            ->save();
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function update(User $user): void
    {
        EloquentUser::where('user_id', $user->userId->value())
            ->update($this->toArray($user));
    }

    /**
     * @param UserEmail $userEmail
     *
     * @return User|null
     *
     * @throws InvalidUuidException|ProfileInvalidImageUrlException
     */
    public function findByEmail(UserEmail $userEmail): ?User
    {
        $user = EloquentUser::where('email', $userEmail->value())
            ->first();

        if ($user === null) {
            return null;
        }

        $firstName = ProfileFirstName::of($user->profile_last_name);
        $lastName = ProfileLastName::of($user->profile_first_name);
        $profile = Profile::restoreFromDB(
            ProfileId::of($user->profile_id),
            ProfileName::of($firstName, $lastName),
            ProfileBirthday::of($user->profile_birthday),
            ProfileGender::Other,
            ProfileImage::of($user->profile_image)
        );

        return User::restoreFromDB(
            UserId::of($user->user_id),
            UserEmail::of($user->email),
            UserHashPassword::of($user->password),
            $profile,
        );
    }

    /**
     * @param User $user
     *
     * @return array<string, mixed>
     */
    private function toArray(User $user): array
    {
        $profile = $user->profile;

        return [
            'user_id' => $user->userId->value(),
            'email' => $user->userEmail->value(),
            'password' => $user->userPassword->value(),
            'profile_id' => $profile->id->value(),
            'profile_last_name' => $profile->name->value()['lastName'],
            'profile_first_name' => $profile->name->value()['firstName'],
            'profile_birth_day' => $profile->birthDay->value(),
            'profile_gender' => $profile->gender->value(),
            'profile_image' => $profile->image->value(),
        ];
    }
}