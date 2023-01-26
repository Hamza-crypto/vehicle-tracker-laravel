<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMetas extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id','meta_key', 'meta_value'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
