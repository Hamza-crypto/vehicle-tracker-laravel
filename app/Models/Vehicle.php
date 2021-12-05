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

        if (isset($request['user']) && $request['user'] != -100 && $request['user'] != 'undefined') {
            $query->where('user_id', '=', $request['user']);
        }

        if (isset($request['used_status']) && $request['used_status'] != -100 && $request['used_status'] != 'undefined') {
            $query->where('used_status', $request['used_status']);
        }

        if (isset($request['gateway']) && $request['gateway'] != '999' && $request['gateway'] != 'undefined') {
            if ($request['gateway'] == 0) {
                $query->whereIn('processed_by', ['0', '']);
            } else {
                $query->where('processed_by', $request['gateway']);
            }

        }

        $query->orderBy('created_at', 'desc');
    }
}
