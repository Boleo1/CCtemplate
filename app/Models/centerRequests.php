<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class centerRequests extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'requests';

    protected $fillable = [
        'event_name',
        'event_type',
        'requested_by',
        'event_date',
        'event_time',
        'event_description',
        'status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'sort_order',
        'created_by',
        'updated_by',
        'end_date',
        'end_time',
        'all_day',
    ];

    protected $casts = [
    'event_date'  => 'date',
    'end_date'    => 'date',
    'all_day'     => 'boolean',
    'reviewed_at' => 'datetime',
    ];
    

    public function reviewer()
    {
      return $this->belongsTo(User::class, 'reviewed_by');
    }
}
