<?php

namespace App\Models\Blogs;

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
    protected $attributes = [

    ];
}
