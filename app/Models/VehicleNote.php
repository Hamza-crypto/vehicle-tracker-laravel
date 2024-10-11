<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class VehicleNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'note'
    ];

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('vehicles_with_notes_300');
        });

        static::updated(function () {
            Cache::forget('vehicles_with_notes_300');
        });

        static::deleted(function () {
            Cache::forget('vehicles_with_notes_300');
        });
    }
}
