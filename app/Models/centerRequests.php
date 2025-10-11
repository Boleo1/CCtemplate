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
        'event_type',
        'requested_by',
        'event_date',
        'event_time',
        'event_description',
    ];
}
