<?php

namespace App\Models;

use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    /**
     * Get all of the tags assigned to this resource.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
