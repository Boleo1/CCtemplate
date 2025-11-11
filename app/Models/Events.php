<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\EventGalleryImage;

class Events extends Model
{
   use HasFactory;
   use SoftDeletes;

   protected $table = 'events';

   protected $fillable = [
       'title',
       'description',
       'slug',
       'community_id',
       'event_type',
       'requested_by',
       'start_at',
       'end_at',
       'all_day',
       'status',
       'published_at',
       'visibility',
       'cover_image_path',
       'created_by',
       'updated_by',
       'thumbnail_image_path',
   ];

   protected $casts = [
    'all_day' => 'boolean',
    'start_at' => 'datetime',
    'end_at' => 'datetime',
    'published_at' => 'datetime',
   ];


   public function creator()
   {
      return $this->belongsTo(User::class, 'created_by');
   }

   public function updater()
   {
      return $this->belongsTo(User::class, 'updated_by');
   }

   public function galleryImages()
   {
      return $this->hasMany(EventGalleryImage::class, 'event_id');
   }

   public function getStartDateAttribute()
  {
      return $this->start_at?->format('Y-m-d');   // for <input type="date">
  }

  public function getStartTimeAttribute()
  {
      return $this->start_at?->format('H:i');     // for <input type="time">
  }
}
