<?php

namespace App\Models;

class Tag extends Model
{
    public $fillable = ['name'];

    /**
     * Get all of the transactions that are assigned this tag.
     */
    public function transactions()
    {
        return $this->morphedByMany(Transaction::class, 'taggable');
    }

    /**
     * Get all of the media that are assigned this tag.
     */
    public function media()
    {
        return $this->morphedByMany(Transaction::class, 'taggable');
    }
}
