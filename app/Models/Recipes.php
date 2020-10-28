<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'thumbnail', 'image', 'content', 'calories', 'fat',
        'protein', 'carb', 'time', 'ingredients'
    ];

    /**
     * Relationship: Favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
