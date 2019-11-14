<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the images/videos for the Story.
     */
    public function items()
    {
        return $this->hasMany(StoryItem::class);
    }

}
