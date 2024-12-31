<?php

namespace App\Models\Utilities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;

    public const HEADER_YES = 1;
    public const HEADER_NO = 0;
    public $table = 'csv_data';
    public $timestamps = true;
    protected $fillable = ['csv_filename', 'csv_header', 'csv_data'];

}
