<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'author','thumbnail', 'image', 'content', 'time'];

     /**
     * Relationship: Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function scopeActive($query)
    {
        return $query->where('id', 89);
    }
}
