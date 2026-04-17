<?php

namespace App\Domains\Users\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const TYPE_CANDIDATE = 'candidate';
    public const TYPE_COMPANY_OWNER = 'company_owner';

    public const TYPES = [
        self::TYPE_CANDIDATE,
        self::TYPE_COMPANY_OWNER,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isCandidate(): bool
    {
        return $this->user_type === self::TYPE_CANDIDATE;
    }

    public function isCompanyOwner(): bool
    {
        return $this->user_type === self::TYPE_COMPANY_OWNER;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
