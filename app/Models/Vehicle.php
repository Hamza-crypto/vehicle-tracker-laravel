<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [

    ];

    public function metas()
    {
        return $this->hasMany(VehicleMetas::class);
    }


    public function scopeFilters($query, $request)
    {
        if (isset($request['make']) && $request['make' ] != -100 && $request['make'] != 'undefined') {
            $query->where('make', $request['make']);
        }

        if (isset($request['model']) && $request['model' ] != -100 && $request['model'] != 'undefined') {
            $query->where('model', $request['model']);
        }

        if (isset($request['status']) && $request['status'] != -100) {

            $search = $request['status'];
            $query->whereHas('metas',function ($q1) use ($search) {
                $q1->where('meta_value', 'LIKE', "%$search%");
            });

        }

        if (isset($request['daterange'])) {
            $dateRange = explode(' - ', $request['daterange']);
            $query->whereBetween('created_at', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }


        $query->orderBy('created_at', 'desc');
    }
}
