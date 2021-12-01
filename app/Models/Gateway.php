<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'api_key' , 'api_secret'];

    public function bins()
    {
        return $this->belongsToMany(BIN::class);
    }

}
