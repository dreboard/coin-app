<?php

namespace App\Models;

use App\Models\Users\UserLogin;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model
 * @see {https://github.com/cybercog/laravel-ban#prepare-bannable-model}
 * @see {https://www.tutsmake.com/laravel-8-check-user-online-or-not-tutorial/?ref=morioh.com&utm_source=morioh.com}
 */
class User extends Authenticatable implements MustVerifyEmail, BannableContract
{
    use HasApiTokens, HasFactory, Notifiable;
    use Impersonate;
    use Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'banned_until'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'banned_until'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime'
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->is_admin == 1;
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return $this->can_be_impersonated == 1;
    }

    /**
     * @param $value
     * @return string
     */
    public function getAccountStatusAttribute($value)
    {
        if ($value == 1){
            return 'Active';
        }
        return 'Suspended';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logins()
    {
        return $this->hasMany(UserLogin::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

}
