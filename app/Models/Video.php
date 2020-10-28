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
    protected $fillable = ['video_Name', 'video_Description', 'video_thumbnail', 'video_Link','video_Quote', 'video_Reps', 'video_Sets'];

        /**
     * Relationship: Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

            /**
     * Relationship: Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function Videocategoriespivot()
    {
        return $this->morphMany(Videocategoriespivot::class, 'Videocategoriespivotable');
    }

            /**
     * Relationship: Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function VideoTypePivot()
    {
        return $this->morphMany(VideoTypePivot::class, 'VideoTypePivotable');
    }

            /**
     * Relationship: Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function MusclePivot()
    {
        return $this->morphMany(MusclePivot::class, 'MusclePivotable');
    }
  }
