<?php

namespace App\Models\Users;

use App\Models\Admin\Roles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const INACTIVE = 0;
    public const ACTIVATED = 1;
    public const GENDER_MALE = 0;
    public const GENDER_FEMALE = 1;
    public const GENDER_OTHER = 2;
    public const ROLE_ADMIN = 1;
    public const ROLE_CUSTOMER = 2;
    public const ROLE_EMPLOYEE = 3;
    public $timestamps = true;
    protected $table = 'users_entity';
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'phone',
        'email',
        'address',
        'password',
        'avatar',
    ];
    protected $attributes = [
        'password' => '123456789',
        'gender' => self::GENDER_OTHER,
        'is_activated' => self::INACTIVE,
    ];

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }

    public function customer()
    {
        return $this->hasOne(Customers::class, 'user_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employees::class, 'user_id', 'id');
    }

    public function getGenderOptions()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
            self::GENDER_OTHER => 'Other',
        ];
    }

    public function getIsActivatedOptions()
    {
        return [
            self::ACTIVATED => 'Activated',
            self::INACTIVE => 'Inactive',
        ];
    }

    public function getRoleOptions()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_CUSTOMER => 'Customer',
            self::ROLE_EMPLOYEE => 'Employee',
        ];
    }

    public function textRole(): Attribute
    {
        return new Attribute(
            get: fn($value, $attribute) => match ($attribute['role_id']) {
                self::ROLE_ADMIN => 'Admin',
                self::ROLE_CUSTOMER => 'Customer',
                self::ROLE_EMPLOYEE => 'Employee',
            },
        );
    }

    public function textIsActivated(): Attribute
    {
        return new Attribute(
            get: fn($value, $attribute) => match ($attribute['is_activated']) {
                self::ACTIVATED => 'Activated',
                self::INACTIVE => 'Inactive',
            },
        );
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function textGender(): Attribute
    {
        return new Attribute(
            get: fn($value, $attribute) => match ($attribute['gender']) {
                self::GENDER_MALE => 'Male',
                self::GENDER_FEMALE => 'Female',
                default => 'Other',
            },
            set: fn($value) => match ($value) {
                'Male' => self::GENDER_MALE,
                'Female' => self::GENDER_FEMALE,
                default => self::GENDER_OTHER,
            }
        );
    }

}
