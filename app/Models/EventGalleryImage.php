<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGalleryImage extends Model
{
    protected $fillable = ['event_id', 'image_path'];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

}
