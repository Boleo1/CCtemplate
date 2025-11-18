<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class contactMessage extends Model
{

    protected $table = 'contact_messages';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'page',
        'ip',
    ];
}
