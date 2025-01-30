<?php

namespace App\Models\Blogs;

use App\Models\Catalogs\Categories;
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
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Categories::class, 'id', 'category_id');
    }
}
