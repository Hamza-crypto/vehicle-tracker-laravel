<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSVHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'database_field',
        'csv_header'
    ];

    protected $table = 'csv_headers';

    public $timestamps = false;

}
