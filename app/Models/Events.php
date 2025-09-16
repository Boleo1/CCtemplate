<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
       'start_at',
       'end_at',
       'all_day',
       'status',
       'published_at',
       'visibility',
       'cover_image_path',
       'created_by',
       'updated_by',
   ];

   protected $casts = [
    'all_day' => 'boolean',
    'start_at' => 'datetime',
    'end_at' => 'datetime',
    'publshed_at' => 'datetime',
   ];

   public function community()
   {
      return $this->belongsTo(Community::class);
   }

   public function creator()
   {
      return $this->belongsTo(User::class, 'created_by');
   }

   public function updater()
   {
      return $this->belongsTo(User::class, 'updated_by');
   }
}
