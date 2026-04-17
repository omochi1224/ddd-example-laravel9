<?php

declare(strict_types=1);

namespace Sample\Infrastructure\EloquentModels;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class EloquentUser extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    public $incrementing = false;

    protected $table = 'users';
    protected $keyType = 'string';

    /**
     * @var array<string>
     */
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
