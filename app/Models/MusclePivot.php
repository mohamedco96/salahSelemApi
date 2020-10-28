<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusclePivot extends Model
{
    public $timestamps = false;
    use HasFactory;
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['muscle_id', 'video_id'];

    /**
     * Relationship: author
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: commentable models
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function MusclePivotable()
    {
        return $this->morphTo();
    }
}
