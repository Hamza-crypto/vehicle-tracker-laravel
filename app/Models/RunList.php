<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_number',
        'lot_number',
        'claim_number',
        'description',
        'number_of_runs',
    ];

    public function vehicle()
    {
        return $this->belongsTo(User::class);
    }
}
