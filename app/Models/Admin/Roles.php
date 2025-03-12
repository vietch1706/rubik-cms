<?php

namespace App\Models\Admin;

use App\Models\Users\Users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';
    public $timestamps = true;

    public const IS_SYSTEM_YES = 1;
    public const IS_SYSTEM_NO = 0;
    protected $fillable = [
        'name',
        'code',
        'description',
    ];
    protected $attributes = [
        'is_system' => self::IS_SYSTEM_YES,
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, 'role_permissions');
    }

    public function users()
    {
        return $this->hasMany(Users::class, 'role_id', 'id');
    }
}
