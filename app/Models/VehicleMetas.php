<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;

class VehicleMetas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['vehicle_id', 'meta_key', 'meta_value'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    protected static function booted()
    {
        static::created(function ($meta) {
            Artisan::call('cache:clear');

            $meta->vehicle->touch();

        });

        static::updated(function ($meta) {
            Artisan::call('cache:clear');

            $meta->vehicle->touch();

        });

        static::deleted(function () {
            Artisan::call('cache:clear');
        });
    }
}
