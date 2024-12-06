<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = [
        'salary',
    ];
    protected $attributes = [
        'salary' => 10000000,
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
