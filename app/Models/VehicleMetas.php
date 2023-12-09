<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleMetas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['vehicle_id', 'meta_key', 'meta_value'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('vehicles_with_days_in_yard');
            Cache::forget('vehicles_sold');
        });

        static::updated(function () {
            Cache::forget('vehicles_with_days_in_yard');
            Cache::forget('vehicles_sold');
        });

        static::deleted(function () {
            Cache::forget('vehicles_with_days_in_yard');
            Cache::forget('vehicles_sold');
        });
    }
}
