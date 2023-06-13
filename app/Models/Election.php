<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'candidate',
        'votes_first_round',
        'percentage_first_round',
        'votes_second_round',
        'percentage_second_round',
        'party',
        'department',
        'year',
        'election',
    ];

    public function scopeFilter($query, $filters)
    {

        if ($filters['candidate']) {
            $query->where('candidate', 'LIKE', '%'.$filters['candidate'].'%');
        }

        if ($filters['votes_first_round']) {
            $query->where('votes_first_round', $filters['votes_first_round']);
        }

        if ($filters['percentage_first_round']) {
            $query->where('percentage_first_round', $filters['percentage_first_round']);
        }

        if ($filters['votes_second_round']) {
            $query->where('votes_second_round', $filters['votes_second_round']);
        }

        if ($filters['percentage_second_round']) {
            $query->where('percentage_second_round', $filters['percentage_second_round']);
        }

        if ($filters['year']) {
            $query->where('year', $filters['year']);
        }
        if ($filters['department']) {
            $query->where('department', 'LIKE', '%'.$filters['department'].'%');
        }
        if ($filters['election']) {
            $query->where('election', 'LIKE', '%'.$filters['election'].'%');
        }
        if ($filters['party']) {
            $query->where('party', 'LIKE', '%'.$filters['party'].'%');
        }

        //order by
        if ($filters['order_by']) {
            $query->orderBy($filters['order_by'], isset($filters['order_by_direction']) ? $filters['order_by_direction'] : 'asc');
        }
    }
}
