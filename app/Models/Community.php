<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $table = 'communities';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'subdomain',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function events()
    {
        return $this->hasMany(Events::class);
    }
}
