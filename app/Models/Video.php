<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['video_Name', 'video_Description', 'video_thumbnail', 'video_Link','video_Quote', 'video_Reps', 'video_Sets', 'catagory', 'type'];
}
