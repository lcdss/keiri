<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Transaction extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'float',
        'paid' => 'boolean',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Touches the parent update timestamp.
     *
     * @var array
     */
    protected $touches = ['parent'];

    /**
     * Get the user that created the resource.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the person that owns the resource.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the company that owns the resource.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the category that the resource belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the parent transaction that the resource belongs to.
     */
    public function parent()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get all of the tags assigned to this resource.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
