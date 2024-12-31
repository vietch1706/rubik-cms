<?php

namespace App\Models\Catalogs\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'campaigns';
    protected $fillable = [
        'name',
        'image',
        'slug',
        'start_date',
        'end_date',
        'status',
    ];
}
