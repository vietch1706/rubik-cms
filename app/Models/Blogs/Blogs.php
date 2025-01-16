<?php

namespace App\Models\Blogs;

use App\Models\Users\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'blogs';
    protected $fillable = [
        'employee_id',
        'title',
        'content',
        'image',
        'slug',
        'image',
        'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}
