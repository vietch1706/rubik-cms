<?php

namespace App\Models\Admin;

use App\Models\Users\Users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'roles_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(Users::class, 'users');
    }
}
